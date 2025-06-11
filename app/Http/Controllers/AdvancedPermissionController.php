<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermissionGroup;
use App\Models\PermissionCategory;
use App\Models\UserPermissionOverride;
use App\Models\PermissionAuditLog;
use App\Services\AdvancedPermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class AdvancedPermissionController extends Controller
{
    protected $permissionService;

    public function __construct(AdvancedPermissionService $permissionService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->permissionService = $permissionService;
    }

    /**
     * عرض لوحة تحكم الصلاحيات المتقدمة
     */
    public function index()
    {
        try {
            $statistics = $this->permissionService->getPermissionStatistics();
            $permissionGroups = $this->permissionService->getPermissionsByGroups();
            $recentLogs = PermissionAuditLog::with(['user', 'auditable'])
                ->recent(7)
                ->latest()
                ->take(10)
                ->get();

            return view('admin.permissions.index', compact(
                'statistics',
                'permissionGroups', 
                'recentLogs'
            ));
        } catch (\Exception $e) {
            Log::error('Error in AdvancedPermissionController@index', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء تحميل صفحة الصلاحيات.');
        }
    }

    /**
     * إدارة صلاحيات المستخدم
     */
    public function manageUser(User $user)
    {
        try {
            $userPermissions = $this->permissionService->getUserPermissions($user);
            $permissionGroups = $this->permissionService->getPermissionsByGroups();
            $overrides = UserPermissionOverride::where('user_id', $user->id)
                ->with(['permission', 'grantedBy'])
                ->latest()
                ->get();
            $auditLogs = PermissionAuditLog::forUser($user->id)
                ->with('user')
                ->latest()
                ->take(20)
                ->get();

            return view('admin.permissions.manage-user', compact(
                'user',
                'userPermissions',
                'permissionGroups',
                'overrides',
                'auditLogs'
            ));
        } catch (\Exception $e) {
            Log::error('Error in AdvancedPermissionController@manageUser', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء تحميل صلاحيات المستخدم.');
        }
    }

    /**
     * منح تجاوز صلاحية
     */
    public function grantOverride(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'permission' => 'required|string|exists:permissions,name',
                'type' => 'required|in:grant,deny',
                'reason' => 'required|string|max:500',
                'expires_at' => 'nullable|date|after:now'
            ]);

            $expiresAt = $validated['expires_at'] ? new \DateTime($validated['expires_at']) : null;

            $override = $this->permissionService->grantPermissionOverride(
                $user,
                $validated['permission'],
                $validated['type'],
                $validated['reason'],
                $expiresAt
            );

            $action = $validated['type'] === 'grant' ? 'منح' : 'منع';
            return back()->with('success', "تم {$action} الصلاحية بنجاح.");

        } catch (\Exception $e) {
            Log::error('Error granting permission override', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء تطبيق التجاوز.');
        }
    }

    /**
     * سحب تجاوز الصلاحية
     */
    public function revokeOverride(Request $request, User $user, UserPermissionOverride $override)
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $success = $this->permissionService->revokePermissionOverride(
                $user,
                $override->permission->name,
                $validated['reason']
            );

            if ($success) {
                return back()->with('success', 'تم سحب التجاوز بنجاح.');
            } else {
                return back()->with('error', 'لم يتم العثور على التجاوز.');
            }

        } catch (\Exception $e) {
            Log::error('Error revoking permission override', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء سحب التجاوز.');
        }
    }

    /**
     * إدارة مجموعات الصلاحيات
     */
    public function manageGroups()
    {
        try {
            $groups = PermissionGroup::with('categories.permissions')
                ->ordered()
                ->get();

            return view('admin.permissions.groups', compact('groups'));
        } catch (\Exception $e) {
            Log::error('Error in AdvancedPermissionController@manageGroups', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء تحميل مجموعات الصلاحيات.');
        }
    }

    /**
     * إنشاء مجموعة صلاحيات جديدة
     */
    public function storeGroup(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:50',
                'color' => 'nullable|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            $group = $this->permissionService->createPermissionGroup($validated);

            return back()->with('success', 'تم إنشاء مجموعة الصلاحيات بنجاح.');

        } catch (\Exception $e) {
            Log::error('Error creating permission group', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء إنشاء مجموعة الصلاحيات.');
        }
    }

    /**
     * تقرير الصلاحيات
     */
    public function report(Request $request)
    {
        try {
            $type = $request->get('type', 'overview');
            $data = [];

            switch ($type) {
                case 'overview':
                    $data = $this->permissionService->getPermissionStatistics();
                    break;
                    
                case 'users':
                    $data['users'] = User::with(['roles', 'permissions'])
                        ->withCount(['roles', 'permissions'])
                        ->paginate(20);
                    break;
                    
                case 'roles':
                    $data['roles'] = Role::with('permissions')
                        ->withCount(['users', 'permissions'])
                        ->get();
                    break;
                    
                case 'overrides':
                    $data['overrides'] = UserPermissionOverride::with(['user', 'permission', 'grantedBy'])
                        ->active()
                        ->latest()
                        ->paginate(20);
                    break;
                    
                case 'audit':
                    $data['logs'] = PermissionAuditLog::with(['user', 'auditable'])
                        ->when($request->get('user_id'), function ($query, $userId) {
                            return $query->where('user_id', $userId);
                        })
                        ->when($request->get('action'), function ($query, $action) {
                            return $query->where('action', $action);
                        })
                        ->latest()
                        ->paginate(20);
                    break;
            }

            return view('admin.permissions.report', compact('type', 'data'));

        } catch (\Exception $e) {
            Log::error('Error generating permission report', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء إنشاء التقرير.');
        }
    }

    /**
     * تنظيف التجاوزات المنتهية الصلاحية
     */
    public function cleanupExpired()
    {
        try {
            $cleanedCount = $this->permissionService->cleanupExpiredOverrides();
            
            return back()->with('success', "تم تنظيف {$cleanedCount} تجاوز منتهي الصلاحية.");

        } catch (\Exception $e) {
            Log::error('Error cleaning up expired overrides', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء تنظيف التجاوزات المنتهية.');
        }
    }

    /**
     * التحقق من تبعيات الصلاحية
     */
    public function checkDependencies(Request $request, User $user)
    {
        try {
            $permission = $request->get('permission');
            $dependencies = $this->permissionService->checkPermissionDependencies($user, $permission);
            
            return response()->json($dependencies);

        } catch (\Exception $e) {
            Log::error('Error checking permission dependencies', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'حدث خطأ أثناء التحقق من التبعيات'], 500);
        }
    }
}