<?php

namespace Zym\Bundle\GaufretteBundle\RackspaceCloudfiles;

if (!class_exists('\CF_Container', false)) {
    require_once 'cloudfiles.php';
}

class Container extends \CF_Container
{
    protected $username;
    protected $apiKey;
    protected $serviceNet;
    protected $containerInitiated = false;

    /**
     * Class constructor
     *
     * Constructor for Container
     */
    function __construct($name, $username, $apiKey, $serviceNet = false)
    {
        $this->name       = $name;
        $this->username   = $username;
        $this->apiKey     = $apiKey;
        $this->serviceNet = $serviceNet;
    }

    protected function initContainer()
    {
        if (!$this->containerInitiated) {
            $auth = new \CF_Authentication($this->username, $this->apiKey);
            $auth->authenticate();

            $conn      = new \CF_Connection($auth, $this->serviceNet);
            $container = $conn->get_container($this->name);
            parent::__construct($container->cfs_auth, $container->cfs_http, $container->name, $container->object_count, $container->bytes_used);
        }

        $this->containerInitiated = true;
    }

    /**
     * String representation of Container
     *
     * Pretty print the Container instance.
     *
     * @return string Container details
     */
    function __toString()
    {
        $this->initContainer();
        return parent::__toString();
    }

    /**
     * Enable Container content to be served via CDN or modify CDN attributes
     *
     * Either enable this Container's content to be served via CDN or
     * adjust its CDN attributes.  This Container will always return the
     * same CDN-enabled URI each time it is toggled public/private/public.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->create_container("public");
     *
     * # CDN-enable the container and set it's TTL for a month
     * #
     * $public_container->make_public(86400/2); # 12 hours (86400 seconds/day)
     * </code>
     *
     * @param int $ttl the time in seconds content will be cached in the CDN
     * @returns string the CDN enabled Container's URI
     * @throws CDNNotEnabledException CDN functionality not returned during auth
     * @throws AuthenticationException if auth token is not valid/expired
     * @throws InvalidResponseException unexpected response
     */
    function make_public($ttl=86400)
    {
        $this->initContainer();
        return parent::make_public($ttl);
    }

    /**
     * Purge Containers objects from CDN Cache.
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     * $container = $conn->get_container("cdn_enabled");
     * $container->purge_from_cdn("user@domain.com");
     * # or
     * $container->purge_from_cdn();
     * # or
     * $container->purge_from_cdn("user1@domain.com,user2@domain.com");
     * @returns boolean True if successful
     * @throws CDNNotEnabledException if CDN Is not enabled on this connection
     * @throws InvalidResponseException if the response expected is not returned
     */
    function purge_from_cdn($email=null)
    {
        $this->initContainer();
        return parent::purge_from_cdn($email);
    }

    /**
     * Enable ACL restriction by User Agent for this container.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->get_container("public");
     *
     * # Enable ACL by Referrer
     * $public_container->acl_referrer("Mozilla");
     * </code>
     *
     * @returns boolean True if successful
     * @throws CDNNotEnabledException CDN functionality not returned during auth
     * @throws AuthenticationException if auth token is not valid/expired
     * @throws InvalidResponseException unexpected response
     */
    function acl_user_agent($cdn_acl_user_agent="") {
        $this->initContainer();
        return parent::acl_user_agent($cdn_acl_user_agent);
    }

    /**
     * Enable ACL restriction by referer for this container.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->get_container("public");
     *
     * # Enable Referrer
     * $public_container->acl_referrer("http://www.example.com/gallery.php");
     * </code>
     *
     * @returns boolean True if successful
     * @throws CDNNotEnabledException CDN functionality not returned during auth
     * @throws AuthenticationException if auth token is not valid/expired
     * @throws InvalidResponseException unexpected response
     */
    function acl_referrer($cdn_acl_referrer="") {
        $this->initContainer();
        return parent::acl_referrer($cdn_acl_referrer);
    }

    /**
     * Enable log retention for this CDN container.
     *
     * Enable CDN log retention on the container. If enabled logs will
     * be periodically (at unpredictable intervals) compressed and
     * uploaded to a ".CDN_ACCESS_LOGS" container in the form of
     * "container_name.YYYYMMDDHH-XXXX.gz". Requires CDN be enabled on
     * the account.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->get_container("public");
     *
     * # Enable logs retention.
     * $public_container->log_retention(True);
     * </code>
     *
     * @returns boolean True if successful
     * @throws CDNNotEnabledException CDN functionality not returned during auth
     * @throws AuthenticationException if auth token is not valid/expired
     * @throws InvalidResponseException unexpected response
     */
    function log_retention($cdn_log_retention=False) {
        $this->initContainer();
        return parent::log_retention($cdn_log_retention);
    }

    /**
     * Disable the CDN sharing for this container
     *
     * Use this method to disallow distribution into the CDN of this Container's
     * content.
     *
     * NOTE: Any content already cached in the CDN will continue to be served
     *       from its cache until the TTL expiration transpires.  The default
     *       TTL is typically one day, so "privatizing" the Container will take
     *       up to 24 hours before the content is purged from the CDN cache.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->get_container("public");
     *
     * # Disable CDN accessability
     * # ... still cached up to a month based on previous example
     * #
     * $public_container->make_private();
     * </code>
     *
     * @returns boolean True if successful
     * @throws CDNNotEnabledException CDN functionality not returned during auth
     * @throws AuthenticationException if auth token is not valid/expired
     * @throws InvalidResponseException unexpected response
     */
    function make_private()
    {
        $this->initContainer();
        return parent::make_private();
    }

    /**
     * Create a new remote storage Object
     *
     * Return a new Object instance.  If the remote storage Object exists,
     * the instance's attributes are populated.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->get_container("public");
     *
     * # This creates a local instance of a storage object but only creates
     * # it in the storage system when the object's write() method is called.
     * #
     * $pic = $public_container->create_object("baby.jpg");
     * </code>
     *
     * @param string $obj_name name of storage Object
     * @return obj CF_Object instance
     */
    function create_object($obj_name=NULL)
    {
        $this->initContainer();
        return parent::create_object($obj_name);
    }

    /**
     * Return an Object instance for the remote storage Object
     *
     * Given a name, return a Object instance representing the
     * remote storage object.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $public_container = $conn->get_container("public");
     *
     * # This call only fetches header information and not the content of
     * # the storage object.  Use the Object's read() or stream() methods
     * # to obtain the object's data.
     * #
     * $pic = $public_container->get_object("baby.jpg");
     * </code>
     *
     * @param string $obj_name name of storage Object
     * @return obj CF_Object instance
     */
    function get_object($obj_name=NULL)
    {
        $this->initContainer();
        return parent::get_object($obj_name);
    }

    /**
     * Return a list of Objects
     *
     * Return an array of strings listing the Object names in this Container.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $images = $conn->get_container("my photos");
     *
     * # Grab the list of all storage objects
     * #
     * $all_objects = $images->list_objects();
     *
     * # Grab subsets of all storage objects
     * #
     * $first_ten = $images->list_objects(10);
     *
     * # Note the use of the previous result's last object name being
     * # used as the 'marker' parameter to fetch the next 10 objects
     * #
     * $next_ten = $images->list_objects(10, $first_ten[count($first_ten)-1]);
     *
     * # Grab images starting with "birthday_party" and default limit/marker
     * # to match all photos with that prefix
     * #
     * $prefixed = $images->list_objects(0, NULL, "birthday");
     *
     * # Assuming you have created the appropriate directory marker Objects,
     * # you can traverse your pseudo-hierarchical containers
     * # with the "path" argument.
     * #
     * $animals = $images->list_objects(0,NULL,NULL,"pictures/animals");
     * $dogs = $images->list_objects(0,NULL,NULL,"pictures/animals/dogs");
     * </code>
     *
     * @param int $limit <i>optional</i> only return $limit names
     * @param int $marker <i>optional</i> subset of names starting at $marker
     * @param string $prefix <i>optional</i> Objects whose names begin with $prefix
     * @param string $path <i>optional</i> only return results under "pathname"
     * @return array array of strings
     * @throws InvalidResponseException unexpected response
     */
    function list_objects($limit=0, $marker=NULL, $prefix=NULL, $path=NULL)
    {
        $this->initContainer();
        return parent::list_objects($limit, $marker, $prefix, $path);
    }

    /**
     * Return an array of Objects
     *
     * Return an array of Object instances in this Container.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $images = $conn->get_container("my photos");
     *
     * # Grab the list of all storage objects
     * #
     * $all_objects = $images->get_objects();
     *
     * # Grab subsets of all storage objects
     * #
     * $first_ten = $images->get_objects(10);
     *
     * # Note the use of the previous result's last object name being
     * # used as the 'marker' parameter to fetch the next 10 objects
     * #
     * $next_ten = $images->list_objects(10, $first_ten[count($first_ten)-1]);
     *
     * # Grab images starting with "birthday_party" and default limit/marker
     * # to match all photos with that prefix
     * #
     * $prefixed = $images->get_objects(0, NULL, "birthday");
     *
     * # Assuming you have created the appropriate directory marker Objects,
     * # you can traverse your pseudo-hierarchical containers
     * # with the "path" argument.
     * #
     * $animals = $images->get_objects(0,NULL,NULL,"pictures/animals");
     * $dogs = $images->get_objects(0,NULL,NULL,"pictures/animals/dogs");
     * </code>
     *
     * @param int $limit <i>optional</i> only return $limit names
     * @param int $marker <i>optional</i> subset of names starting at $marker
     * @param string $prefix <i>optional</i> Objects whose names begin with $prefix
     * @param string $path <i>optional</i> only return results under "pathname"
     * @return array array of strings
     * @throws InvalidResponseException unexpected response
     */
    function get_objects($limit=0, $marker=NULL, $prefix=NULL, $path=NULL, $delimiter=NULL)
    {
        $this->initContainer();
        return parent::get_objects($limit, $marker, $prefix, $path, $delimiter);
    }

    /**
     * Copy a remote storage Object to a target Container
     *
     * Given an Object instance or name and a target Container instance or name, copy copies the remote Object
     * and all associated metadata.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $images = $conn->get_container("my photos");
     *
     * # Copy specific object
     * #
     * $images->copy_object_to("disco_dancing.jpg","container_target");
     * </code>
     *
     * @param obj $obj name or instance of Object to copy
     * @param obj $container_target name or instance of target Container
     * @param string $dest_obj_name name of target object (optional - uses source name if omitted)
     * @param array $metadata metadata array for new object (optional)
     * @param array $headers header fields array for the new object (optional)
     * @return boolean <kbd>true</kbd> if successfully copied
     * @throws SyntaxException invalid Object/Container name
     * @throws NoSuchObjectException remote Object does not exist
     * @throws InvalidResponseException unexpected response
     */
    function copy_object_to($obj,$container_target,$dest_obj_name=NULL,$metadata=NULL,$headers=NULL)
    {
        $this->initContainer();
        return parent::copy_object_to($obj, $container_target, $dest_obj_name, $metadata, $headers);
    }

    /**
     * Copy a remote storage Object from a source Container
     *
     * Given an Object instance or name and a source Container instance or name, copy copies the remote Object
     * and all associated metadata.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $images = $conn->get_container("my photos");
     *
     * # Copy specific object
     * #
     * $images->copy_object_from("disco_dancing.jpg","container_source");
     * </code>
     *
     * @param obj $obj name or instance of Object to copy
     * @param obj $container_source name or instance of source Container
     * @param string $dest_obj_name name of target object (optional - uses source name if omitted)
     * @param array $metadata metadata array for new object (optional)
     * @param array $headers header fields array for the new object (optional)
     * @return boolean <kbd>true</kbd> if successfully copied
     * @throws SyntaxException invalid Object/Container name
     * @throws NoSuchObjectException remote Object does not exist
     * @throws InvalidResponseException unexpected response
     */
    function copy_object_from($obj,$container_source,$dest_obj_name=NULL,$metadata=NULL,$headers=NULL)
    {
        $this->initContainer();
        return parent::copy_object_from($obj, $container_source, $dest_obj_name, $metadata, $headers);
    }

    /**
     * Move a remote storage Object to a target Container
     *
     * Given an Object instance or name and a target Container instance or name, move copies the remote Object
     * and all associated metadata and deletes the source Object afterwards
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $images = $conn->get_container("my photos");
     *
     * # Move specific object
     * #
     * $images->move_object_to("disco_dancing.jpg","container_target");
     * </code>
     *
     * @param obj $obj name or instance of Object to move
     * @param obj $container_target name or instance of target Container
     * @param string $dest_obj_name name of target object (optional - uses source name if omitted)
     * @param array $metadata metadata array for new object (optional)
     * @param array $headers header fields array for the new object (optional)
     * @return boolean <kbd>true</kbd> if successfully moved
     * @throws SyntaxException invalid Object/Container name
     * @throws NoSuchObjectException remote Object does not exist
     * @throws InvalidResponseException unexpected response
     */
    function move_object_to($obj,$container_target,$dest_obj_name=NULL,$metadata=NULL,$headers=NULL)
    {
        $this->initContainer();
        return parent::move_object_to($obj, $container_target, $dest_obj_name, $metadata, $headers);
    }

    /**
     * Move a remote storage Object from a source Container
     *
     * Given an Object instance or name and a source Container instance or name, move copies the remote Object
     * and all associated metadata and deletes the source Object afterwards
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $images = $conn->get_container("my photos");
     *
     * # Move specific object
     * #
     * $images->move_object_from("disco_dancing.jpg","container_target");
     * </code>
     *
     * @param obj $obj name or instance of Object to move
     * @param obj $container_source name or instance of target Container
     * @param string $dest_obj_name name of target object (optional - uses source name if omitted)
     * @param array $metadata metadata array for new object (optional)
     * @param array $headers header fields array for the new object (optional)
     * @return boolean <kbd>true</kbd> if successfully moved
     * @throws SyntaxException invalid Object/Container name
     * @throws NoSuchObjectException remote Object does not exist
     * @throws InvalidResponseException unexpected response
     */
    function move_object_from($obj,$container_source,$dest_obj_name=NULL,$metadata=NULL,$headers=NULL)
    {
        $this->initContainer();
        return parent::move_object_from($obj, $container_source, $dest_obj_name, $metadata, $headers);
    }

    /**
     * Delete a remote storage Object
     *
     * Given an Object instance or name, permanently remove the remote Object
     * and all associated metadata.
     *
     * Example:
     * <code>
     * # ... authentication code excluded (see previous examples) ...
     * #
     * $conn = new CF_Connection($auth);
     *
     * $images = $conn->get_container("my photos");
     *
     * # Delete specific object
     * #
     * $images->delete_object("disco_dancing.jpg");
     * </code>
     *
     * @param obj $obj name or instance of Object to delete
     * @param obj $container name or instance of Container in which the object resides (optional)
     * @return boolean <kbd>True</kbd> if successfully removed
     * @throws SyntaxException invalid Object name
     * @throws NoSuchObjectException remote Object does not exist
     * @throws InvalidResponseException unexpected response
     */
    function delete_object($obj,$container=NULL)
    {
        $this->initContainer();
        return parent::delete_object($obj, $container);
    }

    /**
     * Helper function to create "path" elements for a given Object name
     *
     * Given an Object whos name contains '/' path separators, this function
     * will create the "directory marker" Objects of one byte with the
     * Content-Type of "application/directory".
     *
     * It assumes the last element of the full path is the "real" Object
     * and does NOT create a remote storage Object for that last element.
     */
    function create_paths($path_name)
    {
        $this->initContainer();
        return parent::create_paths($path_name);
    }
}