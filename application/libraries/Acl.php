<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH .'libraries/Zend/Acl.php';

class Acl extends Zend_Acl {
	protected $sessionStaffRoleID = null;
	public static $db_roles = array();
	public static $db_resources = array();
	public static $db_permissions = array();
	public static $staff_type = array(1=>'Smart staff', 2=>'Subscriber');
	
	function __construct() {
		$CI = &get_instance();
		$this->acl = new Zend_Acl();
// var_dump($CI->session->userdata('visitor'));die;
		if (isset($CI->session->userdata('staff')['roles']) && $CI->session->userdata('visitor') == false) {
			$this->sessionStaffRoleID = array_keys($CI->session->userdata('staff')['roles']);
		} elseif (isset($CI->session->userdata['visitor']) && $CI->session->userdata['visitor']) {
			$this->sessionStaffRoleID = 1;
		} else {
			$this->sessionStaffRoleID = null;
		}

		$CI->db->order_by('parent_id', 'ASC'); //Get the roles
		$query = $CI->db->get('smacl_roles');
		$roles = self::$db_roles = $query->result();
// 		print $lastQuery = $CI->db->last_query();
		$CI->db->order_by('parent_id', 'ASC'); //Get the resources
		$query = $CI->db->get('smacl_resources');
		$resources = self::$db_resources = $query->result();
 
		$query = $CI->db->get('smacl_permissions'); //Get the permissions
		$permissions = self::$db_permissions = $query->result();

		foreach ($roles as $item) { //Add the roles to the ACL

			$role = new Zend_Acl_Role($item->role_id);

			$item->parent_id != null ?
				$this->acl->addRole($role,$item->parent_id): 
				$this->acl->addRole($role);
		}

		foreach($resources as $item) { //Add the resources to the ACL
			$resource = new Zend_Acl_Resource($item->resource_id);
			$item->parent_id != null ?
				$this->acl->add($resource, $item->parent_id):
				$this->acl->add($resource);
		}

		foreach($permissions as $perms) { //Add the permissions to the ACL
			$perms->read_access == '1' ? 
				$this->acl->allow($perms->role_id, $perms->resource_id, 'read') : 
				$this->acl->deny($perms->role_id, $perms->resource_id, 'read');
			$perms->write_access == '1' ? 
				$this->acl->allow($perms->role_id, $perms->resource_id, 'write') : 
				$this->acl->deny($perms->role_id, $perms->resource_id, 'write');
			$perms->modify_access == '1' ? 
				$this->acl->allow($perms->role_id, $perms->resource_id, 'modify') : 
				$this->acl->deny($perms->role_id, $perms->resource_id, 'modify');
			$perms->publish_access == '1' ? 
				$this->acl->allow($perms->role_id, $perms->resource_id, 'publish') : 
				$this->acl->deny($perms->role_id, $perms->resource_id, 'publish');
			$perms->delete_access == '1' ? 
				$this->acl->allow($perms->role_id, $perms->resource_id, 'delete') : 
				$this->acl->deny($perms->role_id, $perms->resource_id, 'delete');
			
		}
// 		Administrator inherits nothing, but is allowed all privileges
// 		$acl->allow('2');
	}
	
	/*
	 * Methods to query the ACL.
	 */
	function can_read($role = null, $resource) 
	{
		if (is_null($role)) $role = $this->sessionStaffRoleID;

		if (is_array($role)) {
			foreach ($role as $roleItem) {
				if ($this->can_read($roleItem, $resource)) {
					return TRUE;
				}
			}
			return FALSE;
		}

		return $this->acl->isAllowed($role, $resource, 'read')? TRUE : FALSE;
	}
	
	function can_view($role, $resource) 
	{
		if (is_null($role)) $role = $this->sessionStaffRoleID;
		
		if (is_array($role)) {
			foreach ($role as $roleItem) {
				if ($this->can_view($roleItem, $resource)) {
					return TRUE;
				}
			}
			return FALSE;
		}
		return $this->can_read($role, $resource);
	}
	
	function can_write($role, $resource) 
	{
		if (is_null($role)) $role = $this->sessionStaffRoleID;

		if (is_array($role)) {
			foreach ($role as $roleItem) {
				if ($this->can_write($roleItem, $resource)) {
					return TRUE;
				}
			}
			return FALSE;
		}
		return $this->acl->isAllowed($role, $resource, 'write')? TRUE : FALSE;
	}
	
	function can_modify($role, $resource) 
	{
		if (is_null($role)) $role = $this->sessionStaffRoleID;

		if (is_array($role)) {
			foreach ($role as $roleItem) {
				if ($this->can_modify($roleItem, $resource)) {
					return TRUE;
				}
			}
			return FALSE;
		}
		return $this->acl->isAllowed($role, $resource, 'modify')? TRUE : FALSE;
	}
	
	function can_delete($role, $resource) 
	{
		if (is_null($role)) $role = $this->sessionStaffRoleID;

		if (is_array($role)) {
			foreach ($role as $roleItem) {
				if ($this->can_delete($roleItem, $resource)) {
					return TRUE;
				}
			}
			return FALSE;
		}
		return $this->acl->isAllowed($role, $resource, 'delete')? TRUE : FALSE;
	}
	
    function can_publish($role, $resource) 
    {
    	if (is_null($role)) $role = $this->sessionStaffRoleID;

    	if (is_array($role)) {
    		foreach ($role as $roleItem) {
    			if ($this->can_publish($roleItem, $resource)) {
					return TRUE;
				}
			}
			return FALSE;
    	}
		return $this->acl->isAllowed($role, $resource, 'publish')? TRUE : FALSE;
	}
	
	public static function getAllRoles() 
	{
		if (!self::$db_roles) {
			$CI = &get_instance();
			$CI->db->order_by('parent_id', 'ASC'); //Get the roles
			$query = $CI->db->get('smacl_roles');
			self::$db_roles = $query->result();
		}
		return self::$db_roles;
	}
	
	public static function simpleRoleArray($full = false) 
	{
		$roles = array();
		foreach (self::getAllRoles() as $item) {
			$roles[$item->role_id] = $item->role_name ;
			if ($full && $item->parent_id) {
				$roles[$item->role_id] .= ' &#65513; inherit from: ' . self::getSimpleRoleById($item->parent_id);
			}
		}
		
		return $roles;
	}
	
	public static function getSimpleRoleById($role_id = null)
	{
		if (isset(self::simpleRoleArray()[$role_id]))
			return self::simpleRoleArray()[$role_id];
	
		return null;
	}
	
	public static function getDefaultResponsibleRoleID()
	{
		foreach (self::getAllRoles() as $item) {
			if ($item->is_default_responsible == '1')
				return $item->role_id;
		}
	
		return null;
	}
	
	public static function getResourceById($resource_id = null)
	{
		$CI = &get_instance();
		return $CI->db->select('*')
			->from('smacl_resources')
			->where('resource_id', $resource_id)
			->get()->row();
	}
	
	public static function getAllResources()
	{
		if (!self::$db_resources) {
			$CI = &get_instance();
			$CI->db->order_by('parent_id', 'ASC'); //Get the resource
			$query = $CI->db->get('smacl_resources');
			self::$db_resources = $query->result();
		}
		return self::$db_resources;
	}
	
	public static function simpleResourcesArray()
	{
		$resources = array();
		foreach (self::getAllResources() as $item) {
			$resources[$item->resource_id] = $item->resource_name;
		}
	
		return $resources;
	}
	
	public static function getSimpleResourceById($resource_id = null)
	{
		if (isset(self::simpleResourcesArray()[$resource_id]))
			return self::simpleResourcesArray()[$resource_id];
	
		return null;
	}
	
}