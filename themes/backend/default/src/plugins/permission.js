class PermissionManager
{
    constructor()
    {
        this.permissions = [];
    }
    set(permissions)
    {
        this.permissions = permissions;
    }
    has(name)
    {
        let permission = this.permissions.find(p => p.name === name);

        return permission && permission.value === '1';
    }
}

let permissionManager = new PermissionManager();

export default {
    install(Vue, options)
    {
        Vue.prototype.$permission = permissionManager;

        Vue.directive('permission', {
            inserted(el, binding)
            {
                let requiredPermission = binding.value;

                if (requiredPermission
                    && requiredPermission.length > 0
                    && permissionManager.has(requiredPermission) === false)
                {
                    el.style.display = 'none';
                }
            }
        })
    }
}