<div class="space-y-8 p-6 bg-white rounded-lg shadow">
    @foreach($groupedPermissions as $moduleName => $permissions)
        <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
            <h3 class="text-lg font-bold text-gray-900 mb-4 uppercase tracking-wider">
                Moduł: {{ Str::headline($moduleName) }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permissions as $permission)
                    @php
                        // Jeśli przekazałeś obiekty Eloquent, użyj $permission->name, jeśli czyste stringi - $permission
                        $permissionName = is_string($permission) ? $permission : $permission->name;
                    @endphp

                    <div class="flex items-center p-3 bg-gray-50 rounded border border-gray-100">
                        <label class="flex items-center space-x-3 cursor-pointer w-full">
                            <input type="checkbox" name="permissions[]" value="{{ $permissionName }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 font-medium">
                                <!-- Pokazuje 'users.create' zamiast całego prefiksu dla lepszej czytelności -->
                                {{ str_replace($moduleName . '.', '', $permissionName) }}
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>