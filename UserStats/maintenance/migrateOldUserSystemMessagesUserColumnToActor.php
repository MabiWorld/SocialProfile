<?php
/**
 * @file
 * @ingroup Maintenance
 */
$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../../..';
}
require_once "$IP/maintenance/Maintenance.php";

/**
 * Run automatically with update.php
 *
 * @since January 2020
 */
class MigrateOldUserSystemMessagesUserColumnToActor extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrates data from old _user_name/_user_id columns in the user_system_messages table to the new actor column.' );
	}

	/**
	 * Get the update key name to go in the update log table
	 *
	 * @return string
	 */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/**
	 * Message to show that the update was done already and was just skipped
	 *
	 * @return string
	 */
	protected function updateSkippedMessage() {
		return 'user_system_messages has already been migrated to use the actor column.';
	}

	/**
	 * Do the actual work.
	 *
	 * @return bool True to log the update as done
	 */
	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_MASTER );

		if ( !$dbw->fieldExists( 'user_system_messages', 'um_user_id', __METHOD__ ) ) {
			return true;
		}

		$res = $dbw->select(
			'user_system_messages',
			[
				'um_user_name'
			]
		);
		foreach ( $res as $row ) {
			$user = new User();
			$user->setName( $row->um_user_name );
			$dbw->update(
				'user_system_messages',
				[
					'um_actor' => $user->getActorId( $dbw )
				],
				[
					'um_user_name' => $row->um_user_name
				]
			);
		}

		return true;
	}
}

$maintClass = MigrateOldUserSystemMessagesUserColumnToActor::class;
require_once RUN_MAINTENANCE_IF_MAIN;
