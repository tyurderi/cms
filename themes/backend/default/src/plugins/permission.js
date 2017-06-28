class PermissionManager
{
    constructor()
    {
        this.permissions = [];
    }

    /**
     * Add permission to the permisson manager.
     *
     * @param permissions
     */
    set(permissions)
    {
        this.permissions = permissions;
    }

    /**
     * This method checks by the passed input value for valid permissions.
     *
     * Possible are combinations to check if two permissions are given:
     * PermissionManager.has('permission1&permission2')
     *
     * Also if at least one permission is given:
     * PermissionManager.has('permission1|permission2')
     *
     * Or in combination
     * PermissionManager.has('permission1|permission2&permission3')
     *
     * In the last example the permission1 must be valid OR permission2 AND permission3 must be valid.
     *
     * @param input
     * @returns {boolean}
     */
    has(input)
    {
        if (input === '')
        {
            return true;
        }

        let orFields = input.split('|');

        for (let i = 0; i < orFields.length; i++)
        {
            if (orFields[i] === '')
            {
                continue;
            }

            let andFields = orFields[i].split('&'),
                passes    = 0;

            for (let k = 0; k < andFields.length; k++)
            {
                let permission = this.permissions.find(p => p.name === andFields[k]);

                if (permission && permission.value === '1')
                {
                    passes++;
                }
            }

            if (passes === andFields.length)
            {
                return true;
            }
        }

        return false;
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