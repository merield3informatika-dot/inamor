<x-app-layout>

<div class="max-w-5xl mx-auto px-6 py-8">


    <div class="bg-white rounded-xl shadow border mt-6">

        <div class="p-6 border-b">

            <h2 class="text-xl font-semibold">
                Workspace Roles
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Available roles in this workspace.
            </p>

        </div>

        <table class="w-full">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="text-left px-6 py-3">
                        Role
                    </th>

                    <th class="text-left px-6 py-3">
                        Description
                    </th>

                </tr>

            </thead>

            <tbody>

            @foreach($roles as $role)

                <tr class="border-b">

                    <td class="px-6 py-4 font-medium">
                        {{ $role['name'] }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $role['description'] }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>