<div class="mb-8 overflow-x-auto">
    <nav class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700">

        <a
            href="{{ route('workspace.settings.edit') }}"
            @class([
                'px-4 py-3 text-sm font-medium transition rounded-t-lg',
                'border-b-2 border-indigo-600 text-indigo-600'
                    => request()->routeIs('workspace.settings.edit'),

                'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'
                    => ! request()->routeIs('workspace.settings.edit'),
            ])
        >
            General
        </a>

        <a
            href="{{ route('workspace.invitation.show') }}"
            @class([
                'px-4 py-3 text-sm font-medium transition rounded-t-lg',
                'border-b-2 border-indigo-600 text-indigo-600'
                    => request()->routeIs('workspace.invitation.*'),

                'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'
                    => ! request()->routeIs('workspace.invitation.*'),
            ])
        >
            Invitation
        </a>

        <a
            href="{{ route('workspace.members.index') }}"
            @class([
                'px-4 py-3 text-sm font-medium transition rounded-t-lg',
                'border-b-2 border-indigo-600 text-indigo-600'
                    => request()->routeIs('workspace.members.*'),

                'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'
                    => ! request()->routeIs('workspace.members.*'),
            ])
        >
            Members
        </a>

        <a
            href="{{ route('workspace.roles.index') }}"
            @class([
                'px-4 py-3 text-sm font-medium transition rounded-t-lg',
                'border-b-2 border-indigo-600 text-indigo-600'
                    => request()->routeIs('workspace.roles.*'),

                'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'
                    => ! request()->routeIs('workspace.roles.*'),
            ])
        >
            Roles
        </a>

        <a
            href="{{ route('workspace.settings.danger') }}"
            @class([
                'ml-auto px-4 py-3 text-sm font-medium transition rounded-t-lg',
                'border-b-2 border-red-500 text-red-500'
                    => request()->routeIs('workspace.settings.danger'),

                'text-gray-500 hover:text-red-500'
                    => ! request()->routeIs('workspace.settings.danger'),
            ])
        >
            Danger Zone
        </a>

    </nav>
</div>