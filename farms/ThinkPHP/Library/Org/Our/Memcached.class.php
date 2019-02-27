<?php
namespace Org\Our;

class Memcached{
	
	public __construct([ string $persistent_id ]);
	public bool add (string $key ，mixed $value [，int $expiration ]);
	public bool addByKey (string $server_key ，string $key ，mixed $value [，int $expiration ]);
	public bool addServer (string $host ，int $port [，int $weight= 0 ]);
	public bool addServers (array $servers );
	public bool append (string $key ，string $value );
	public bool appendByKey (string $server_key ，string $key ，string $value );
	public bool cas (float $cas_token ，string $key ，mixed $value [，int $expiration ]);
	public bool casByKey (float $cas_token ，string $server_key ，string $key ，mixed $value [，int $expiration ]);
	public int decrement (string $key [，int $offset= 1 [，int $initial_value= 0 [，int $expiry= 0 ]]]);
	public int decrementByKey (string $server_key ，string $key [，int $offset= 1 [，int $initial_value= 0 [，int $expiry= 0 ]]]);
	public bool delete (string $key [，int $time= 0 ]);
	public bool deleteByKey (string $server_key ，string $key [，int $time= 0 ]);
	public array deleteMulti (array $keys [，int $time= 0 ]);
	public bool deleteMultiByKey (string $server_key ，array $keys [，int $time= 0 ]);
	public array fetch (void );
	public array fetchAll (void );
	public bool flush ([ int $delay= 0 ]);
	public mixed get (string $key [，callable $cache_cb [，float &$cas_token ]]);
	public array getAllKeys (void );
	public mixed getByKey (string $server_key ，string $key [，callable $cache_cb [，float &$cas_token ]]);
	public bool getDelayed (array $keys [，bool $with_cas [，callable $value_cb ]]);
	public bool getDelayedByKey (string $server_key ，array $keys [，bool $with_cas [，callable $value_cb ]]);
	public mixed getMulti (array $keys [，array &$cas_tokens [，int $flags ]]);
	public array getMultiByKey (string $server_key ，array $keys [，string &$cas_tokens [，int $flags ]]);
	public mixed getOption (int $option );
	public int getResultCode (void );
	public string getResultMessage (void );
	public array getServerByKey (string $server_key );
	public array getServerList (void );
	public array getStats (void );
	public array getVersion (void );
	public int increment (string $key [，int $offset= 1 [，int $initial_value= 0 [，int $expiry= 0 ]]]);
	public int incrementByKey (string $server_key ，string $key [，int $offset= 1 [，int $initial_value= 0 [，int $expiry= 0 ]]]);
	public bool isPersistent (void );
	public bool isPristine (void );
	public bool prepend (string $key ，string $value );
	public bool prependByKey (string $server_key ，string $key ，string $value );
	public bool quit (void );
	public bool replace (string $key ，mixed $value [，int $expiration ]);
	public bool replaceByKey (string $server_key ，string $key ，mixed $value [，int $expiration ]);
	public bool resetServerList (void );
	public bool set (string $key ，mixed $value [，int $expiration ]);
	public bool setByKey (string $server_key ，string $key ，mixed $value [，int $expiration ]);
	public bool setMulti (array $items [，int $expiration ]);
	public bool setMultiByKey (string $server_key ，array $items [，int $expiration ]);
	public bool setOption (int $option ，mixed $value );
	public bool setOptions (array $options );
	public void setSaslAuthData (string $username ，string $password );
	public bool touch (string $key ，int $expiration );
	public bool touchByKey (string $server_key ，string $key ，int $expiration );
}




?>
