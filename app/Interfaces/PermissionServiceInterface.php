<?php
namespace App\Interfaces;
use Illuminate\Support\Collection as SupportCollection;

interface PermissionServiceInterface
{
    public function getAllPermissions(): SupportCollection;
    // Methods for managing permissions directly via API could be added here if needed,
    // but it's generally safer to manage them via roles or seeders.
}
