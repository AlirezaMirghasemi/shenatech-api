<?php

return [
    // Roles
    'roles' => [
        'created' => 'Role created successfully.',
        'updated' => 'Role updated successfully.',
        'deleted' => 'Role deleted successfully.',
        'restored' => 'Role restored successfully.',
        'permissions_assigned' => 'Permissions assigned to role successfully.',
        'permissions_revoked' => 'Permissions revoked from role successfully.',
        'users_assigned' => 'Users assigned to role successfully.',
        'users_revoked' => 'Users revoked from role successfully.',
        'not_found' => 'Role not found.',
        'name_not_unique' => 'Role name already exists.',
        'cannot_delete_admin' => 'Admin role cannot be deleted.',
        'validation_failed' => 'Role input validation failed.',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Permission created successfully.',
        'updated' => 'Permission updated successfully.',
        'deleted' => 'Permission deleted successfully.',
        'not_found' => 'Permission not found.',
        'name_not_unique' => 'Permission name already exists.',
        'validation_failed' => 'Permission input validation failed.',
    ],

    // Users
    'users' => [
        'created' => 'User created successfully.',
        'updated' => 'User updated successfully.',
        'deleted' => 'User deleted successfully.',
        'restored' => 'User restored successfully.',
        'not_found' => 'User not found.',
        'email_not_unique' => 'User email already exists.',
        'role_assigned' => 'Role assigned to user successfully.',
        'role_revoked' => 'Role revoked from user successfully.',
        'validation_failed' => 'User input validation failed.',
    ],

    // tags
    'tags' => [
        'created' => 'Tag created successfully.',
        'updated' => 'Tag updated successfully.',
        'deleted' => 'Tag deleted successfully.',
        'not_found' => 'Tag not found.',
        'name_not_unique' => 'Tag name already exists.',
        'validation_failed' => 'Tag input validation failed.',
    ],

    // Generic / common errors
    'generic' => [
        'validation_failed' => 'Validation failed.',
        'unauthorized' => 'This action is unauthorized.',
        'forbidden' => 'Forbidden.',
        'not_found' => 'Resource not found.',
        'server_error' => 'Internal server error. Please try again later.',
    ],
];
