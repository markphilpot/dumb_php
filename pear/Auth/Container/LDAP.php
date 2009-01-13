<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Jan Wagner <wagner@netsols.de>                              |
// +----------------------------------------------------------------------+
//
// $Id: LDAP.php,v 1.14 2003/06/02 16:55:10 mj Exp $
//

require_once "Auth/Container.php";
require_once "PEAR.php";

/**
 * Storage driver for fetching login data from LDAP
 *
 * This class is heavily based on the DB and File containers. By default it
 * connects to localhost:389 and searches for uid=$username with the scope
 * "sub". If no search base is specified, it will try to determine it via
 * the namingContexts attribute. It takes its parameters in a hash, connects
 * to the ldap server, binds anonymously, searches for the user, and tries
 * to bind as the user with the supplied password. When a group was set, it
 * will look for group membership of the authenticated user. If all goes
 * well the authentication was successful.
 *
 * Parameters:
 *
 * host:        localhost (default), ldap.netsols.de or 127.0.0.1
 * port:        389 (default) or 636 or whereever your server runs
 * url:         ldap://localhost:389/
 *              useful for ldaps://, works only with openldap2 ?
 *              it will be preferred over host and port
 * binddn:      If set, searching for user will be done after binding
 *              as this user, if not set the bind will be anonymous.
 *              This is reported to make the container work with MS
 *              Active Directory, but should work with any server that
 *              is configured this way.
 *              This has to be a complete dn for now (basedn and
 *              userdn will not be appended).
 * bindpw:      The password to use for binding with binddn
 * scope:       one, sub (default), or base
 * basedn:      the base dn of your server
 * userdn:      gets prepended to basedn when searching for user
 * userattr:    the user attribute to search for (default: uid)
 * useroc:      objectclass of user (for the search filter)
 *              (default: posixAccount)
 * groupdn:     gets prepended to basedn when searching for group
 * groupattr  : the group attribute to search for (default: cn)
 * groupoc    : objectclass of group (for the search filter)
 *              (default: groupOfUniqueNames)
 * memberattr : the attribute of the group object where the user dn
 *              may be found (default: uniqueMember)
 * memberisdn:  whether the memberattr is the dn of the user (default)
 *              or the value of userattr (usually uid)
 * group:       the name of group to search for
 * debug:       Enable/Disable debugging output (default: false)
 *
 * To use this storage container, you have to use the following syntax:
 *
 * <?php
 * ...
 *
 * $a = new Auth("LDAP", array(
 *       'host' => 'localhost',
 *       'port' => '389',
 *       'basedn' => 'o=netsols,c=de',
 *       'userattr' => 'uid'
 *       'binddn' => 'cn=admin,o=netsols,c=de',
 *       'bindpw' => 'password'));
 *
 * $a2 = new Auth('LDAP', array(
 *       'url' => 'ldaps://ldap.netsols.de',
 *       'basedn' => 'o=netsols,c=de',
 *       'scope' => 'one',
 *       'userdn' => 'ou=People',
 *       'groupdn' => 'ou=Groups',
 *       'groupoc' => 'posixGroup',
 *       'memberattr' => 'memberUid',
 *       'memberisdn' => false,
 *       'group' => 'admin'
 *       ));
 *
 * $a3 = new Auth('LDAP', array(
 *         'host' => 'ad.netsols.de',
 *         'basedn' => 'dc=netsols,dc=de',
 *         'userdn' => 'ou=Users',
 *         'binddn' => 'cn=Jan Wagner,ou=Users,dc=netsols,dc=de',
 *         'bindpw' => '*******',
 *         'userattr' => 'samAccountName',
 *         'useroc' => 'user',
 *          'debug' => true
 *         ));             
 *
 * The parameter values have to correspond
 * to the ones for your LDAP server of course.
 *
 * When talking to a Microsoft ActiveDirectory server you have to
 * use 'samaccountname' as the 'userattr' and follow special rules
 * to translate the ActiveDirectory directory names into 'basedn'.
 * The 'basedn' for the default 'Users' folder on an ActiveDirectory
 * server for the ActiveDirectory Domain (which is not related to
 * its DNS name) "win2000.example.org" would be:
 * "CN=Users, DC=win2000, DC=example, DC=org'
 * where every component of the domain name becomes a DC attribute
 * of its own. If you want to use a custom users folder you have to
 * replace "CN=Users" with a sequence of "OU" attributes that specify
 * the path to your custom folder in reverse order.
 * So the ActiveDirectory folder
 *   "win2000.example.org\Custom\Accounts"
 * would become
 *   "OU=Accounts, OU=Custom, DC=win2000, DC=example, DC=org'
 *
 * It seems that binding anonymously to an Active Directory
 * is not allowed, so you have to set binddn and bindpw for
 * user searching,
 *
 * Example a3 shows a tested example for connenction to Windows 2000
 * Active Directory
 *
 * @author   Jan Wagner <wagner@netsols.de>
 * @package  Auth
 * @version  $Revision: 1.14 $
 */
class Auth_Container_LDAP extends Auth_Container
{
    /**
     * Options for the class
     * @var array
     */
    var $options = array();

    /**
     * Connection ID of LDAP Link
     * @var string
     */
    var $conn_id = false;

    /**
     * LDAP search function to use
     * @var string
     */
    var $ldap_search_func;

    /**
     * Constructor of the container class
     *
     * @param  $params, associative hash with host,port,basedn and userattr key
     * @return object Returns an error object if something went wrong
     */
    function Auth_Container_LDAP($params)
    {
        $this->_setDefaults();

        if (is_array($params)) {
            $this->_parseOptions($params);
        }
    }

    // }}}
    // {{{ _connect()

    /**
     * Connect to the LDAP server using the global options
     *
     * @access private
     * @return object  Returns a PEAR error object if an error occurs.
     */
    function _connect()
    {
        // connect
        if (isset($this->options['url']) && $this->options['url'] != '') {
            $this->_debug('Connecting with URL', __LINE__);
            $conn_params = array($this->options['url']);
        } else {
            $this->_debug('Connecting with host:port', __LINE__);
            $conn_params = array($this->options['host'], $this->options['port']);
        }

        if(($this->conn_id = @call_user_func_array('ldap_connect', $conn_params)) === false) {
            return PEAR::raiseError('Auth_Container_LDAP: Could not connect to server.', 41, PEAR_ERROR_DIE);
        }
        $this->_debug('Successfully connected to server', __LINE__);

        // try switchig to LDAPv3
        $ver = 0;
        if(@ldap_get_option($this->conn_id, LDAP_OPT_PROTOCOL_VERSION, $ver) && $ver >= 2) {
            $this->_debug('Switching to LDAPv3', __LINE__);
            @ldap_set_option($this->conn_id, LDAP_OPT_PROTOCOL_VERSION, 3);
        }

        // bind with credentials or anonymously
        if($this->options['binddn'] && $this->options['bindpw']) {
            $this->_debug('Binding with credentials', __LINE__);
            $bind_params = array($this->conn_id, $this->options['binddn'], $this->options['bindpw']);
        } else {
            $this->_debug('Binding anonymously', __LINE__);
            $bind_params = array($this->conn_id);
        }
        
        // bind for searching
        if ((@call_user_func_array('ldap_bind', $bind_params)) == false) {
            $this->_debug();
            $this->_disconnect();
            return PEAR::raiseError("Auth_Container_LDAP: Could not bind to LDAP server.", 41, PEAR_ERROR_DIE);
        }
        $this->_debug('Binding was successful', __LINE__);
    }

    /**
     * Disconnects (unbinds) from ldap server
     *
     * @access private
     */
    function _disconnect() 
    {
        if($this->_isValidLink()) {
            $this->_debug('disconnecting from server');
            @ldap_unbind($this->conn_id);
        }
    }

    /**
     * Tries to find Basedn via namingContext Attribute
     *
     * @access private
     */
    function _getBaseDN()
    {
        if ($this->options['basedn'] == "" && $this->_isValidLink()) {           
            $this->_debug("basedn not set, searching via namingContexts.", __LINE__);

            $result_id = @ldap_read($this->conn_id, "", "(objectclass=*)", array("namingContexts"));
            
            if (ldap_count_entries($this->conn_id, $result_id) == 1) {
                
                $this->_debug("got result for namingContexts", __LINE__);
                
                $entry_id = ldap_first_entry($this->conn_id, $result_id);
                $attrs = ldap_get_attributes($this->conn_id, $entry_id);
                $basedn = $attrs['namingContexts'][0];

                if ($basedn != "") {
                    $this->_debug("result for namingContexts was $basedn", __LINE__);
                    $this->options['basedn'] = $basedn;
                }
            }
            ldap_free_result($result_id);
        }

        // if base ist still not set, raise error
        if ($this->options['basedn'] == "") {
            return PEAR::raiseError("Auth_Container_LDAP: LDAP search base not specified!", 41, PEAR_ERROR_DIE);
        }        
        return true;
    }

    /**
     * determines whether there is a valid ldap conenction or not
     *
     * @accessd private
     * @return boolean
     */
    function _isValidLink() 
    {
        if(is_resource($this->conn_id)) {
            if(get_resource_type($this->conn_id) == 'ldap link') {
                return true;
            }
        }
        return false;
    }

    /**
     * Set some default options
     *
     * @access private
     */
    function _setDefaults()
    {
        $this->options['host']        = 'localhost';
        $this->options['port']        = '389';
        $this->options['binddn']      = '';
        $this->options['bindpw']      = '';
        $this->options['scope']       = 'sub';
        $this->options['basedn']      = '';
        $this->options['userdn']      = '';
        $this->options['userattr']    = "uid";
        $this->options['useroc']      = 'posixAccount';
        $this->options['groupdn']     = '';
        $this->options['groupattr']   = 'cn';
        $this->options['groupoc']     = 'groupOfUniqueNames';
        $this->options['memberattr']  = 'uniqueMember';
        $this->options['memberisdn']  = true;
        $this->options['debug']       = false;
    }

    /**
     * Parse options passed to the container class
     *
     * @access private
     * @param  array
     */
    function _parseOptions($array)
    {
        foreach ($array as $key => $value) {
            $this->options[$key] = $value;
        }

        // get the according search function for selected scope
        switch($this->options['scope']) {
        case 'one':
            $this->ldap_search_func = 'ldap_list';
            break;
        case 'base':
            $this->ldap_search_func = 'ldap_read';
            break;
        default:
            $this->ldap_search_func = 'ldap_search';
            break;
        }
        $this->_debug("LDAP search function will be: {$this->ldap_search_func}", __LINE__);
    }

    /**
     * Fetch data from LDAP server
     *
     * Searches the LDAP server for the given username/password
     * combination.
     *
     * @param  string Username
     * @param  string Password
     * @return boolean
     */
    function fetchData($username, $password)
    {        

        $this->_connect();
        $this->_getBaseDN();
        
        // make search filter
        $filter = sprintf('(&(objectClass=%s)(%s=%s))', $this->options['useroc'], $this->options['userattr'], $username);

        // make search base dn
        $search_basedn = $this->options['userdn'];
        if ($search_basedn != '' && substr($search_basedn, -1) != ',') {
            $search_basedn .= ',';
        }
        $search_basedn .= $this->options['basedn'];
        
        // make functions params array
        $func_params = array($this->conn_id, $search_basedn, $filter, array($this->options['userattr']));

        $this->_debug("Searching with $filter in $search_basedn", __LINE__);

        // search
        if (($result_id = @call_user_func_array($this->ldap_search_func, $func_params)) == false) {
            $this->_debug('User not found', __LINE__);
        } elseif (ldap_count_entries($this->conn_id, $result_id) == 1) { // did we get just one entry?

            $this->_debug('User was found', __LINE__);
            
            // then get the user dn
            $entry_id = ldap_first_entry($this->conn_id, $result_id);
            $user_dn  = ldap_get_dn($this->conn_id, $entry_id);

            ldap_free_result($result_id);

            // need to catch an empty password as openldap seems to return TRUE
            // if anonymous binding is allowed
            if ($password != "") {
                $this->_debug("Bind as $user_dn", __LINE__);                

                // try binding as this user with the supplied password
                if (@ldap_bind($this->conn_id, $user_dn, $password)) {
                    $this->_debug('Bind successful', __LINE__);

                    // check group if appropiate
                    if(isset($this->options['group'])) {
                        // decide whether memberattr value is a dn or the username
                        $this->_debug('Checking group membership', __LINE__);
                        return $this->checkGroup(($this->options['memberisdn']) ? $user_dn : $username);
                    } else {
                        $this->_debug('Authenticated', __LINE__);
                        $this->_disconnect();
                        return true; // user authenticated
                    } // checkGroup
                } // bind
            } // non-empty password
        } // one entry
        // default
        $this->_debug('NOT authenticated!', __LINE__);
        $this->_disconnect();
        return false;
    }

    /**
     * Validate group membership
     *
     * Searches the LDAP server for group membership of the
     * authenticated user
     *
     * @param  string Distinguished Name of the authenticated User
     * @return boolean
     */
    function checkGroup($user) 
    {
        // make filter
        $filter = sprintf('(&(%s=%s)(objectClass=%s)(%s=%s))',
                          $this->options['groupattr'],
                          $this->options['group'],
                          $this->options['groupoc'],
                          $this->options['memberattr'],
                          $user
                          );

        // make search base dn
        $search_basedn = $this->options['groupdn'];
        if($search_basedn != '' && substr($search_basedn, -1) != ',') {
            $search_basedn .= ',';
        }
        $search_basedn .= $this->options['basedn'];
        
        $func_params = array($this->conn_id, $search_basedn, $filter, array($this->options['memberattr']));

        $this->_debug("Searching with $filter in $search_basedn", __LINE__);
        
        // search
        if(($result_id = @call_user_func_array($this->ldap_search_func, $func_params)) != false) {
            if(ldap_count_entries($this->conn_id, $result_id) == 1) {                
                ldap_free_result($result_id);
                $this->_debug('User is member of group', __LINE__);
                $this->_disconnect();
                return true;
            }
        }

        // default
        $this->_debug('User is NOT member of group', __LINE__);
        $this->_disconnect();
        return false;
    }

    /**
     * Outputs debugging messages
     *
     * @access private
     * @param string Debugging Message
     * @param integer Line number
     */
    function _debug($msg = '', $line = 0)
    {
        if($this->options['debug'] === true) {
            if($msg == '' && $this->_isValidLink()) {
                $msg = 'LDAP_Error: ' . @ldap_err2str(@ldap_errno($this->_conn_id));
            }
            print("$line: $msg <br />");
        }
    }
}

?>
