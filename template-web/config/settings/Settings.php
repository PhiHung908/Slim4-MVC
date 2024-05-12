<?php

declare(strict_types=1);

namespace App\config\settings;

use Monolog\Logger;

class Settings implements SettingsInterface
{
    private array $settings;

    public function __construct(array &$settings, &$psr4PathHelper, &$containerBuilder = null)
	{		
		$psr4PathHelper->prependPrs4Alias(__DIR__, $containerBuilder);
		
        $this->settings = array_merge($settings, [
			'temp' => $psr4PathHelper->aliasToFull('App/var/temp',true),
		]);
    }

    /**
     * @return mixed
     */
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key] ?? null;
    }
	
	public function set(array $oneKeyVal, $val = null)
    {
		if (!is_array($oneKeyVal) && null===$val) return;
		if (is_string($oneKeyVal)) $oneKeyVal = [$oneKeyVal => $val];
		$this->settings = array_merge($this->settings, $oneKeyVal);
    }
}
