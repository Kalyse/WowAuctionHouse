<?php
/**
 * Blizzard battle.net configuration
 *
 * @todo rename class
 * @author TLamy
 */
class Wow
{
	const REGION_EU = 'eu';
	const REGION_US = 'us';

	static private $_bnetConfig = array(
			'eu'=>array('host'=>'eu.battle.net'),
			'us'=>array('host'=>'us.battle.net'),
			);

	public function getBnetHostname( $region)
	{
		if( !array_key_exists($region, static::$_bnetConfig)) {
			throw new Exception("Region not found or not configured");
		}
		return static::$_bnetConfig[$region]['host'];
	}
}