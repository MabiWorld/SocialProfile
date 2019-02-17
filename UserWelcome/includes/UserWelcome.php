<?php
/**
 * UserWelcome extension
 * Adds <welcomeUser/> tag to display user-specific social information
 *
 * @file
 * @ingroup Extensions
 * @author David Pean <david.pean@gmail.com>
 * @author Jack Phoenix
 * @link https://www.mediawiki.org/wiki/Extension:UserWelcome Documentation
 * @license GPL-2.0-or-later
 */

class UserWelcome {
	/**
	 * Register <welcomeUser /> tag with the parser
	 *
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( &$parser ) {
		$parser->setHook( 'welcomeUser', [ __CLASS__, 'getWelcomeUser' ] );
	}

	public static function getWelcomeUser( $input, $args, $parser ) {
		$parser->getOutput()->updateCacheExpiry( 0 );
		$parser->getOutput()->addModuleStyles( 'ext.socialprofile.userwelcome.css' );
		// This is so stupid. The callback to onParserFirstCallInit() is
		// *always* (assumed to be) static even if you don't declare it as such.
		// So obviously using $this in a static function fails...grumble grumble.
		$uw = new UserWelcome;
		$output = $uw->getWelcome();
		return $output;
	}

	function getWelcome() {
		global $wgUser, $wgLang;

		// Get stats and user level
		$stats = new UserStats( $wgUser->getId(), $wgUser->getName() );
		$stats_data = $stats->getUserStats();
		$user_level = new UserLevel( $stats_data['points'] );

		// Safe links
		$level_link = Title::makeTitle( NS_HELP, wfMessage( 'mp-userlevels-link' )->inContentLanguage()->plain() );
		$avatar_link = SpecialPage::getTitleFor( 'UploadAvatar' );

		// Make an avatar
		$avatar = new wAvatar( $wgUser->getId(), 'l' );

		// Profile top images/points
		$output = '<div class="mp-welcome-logged-in">
		<div class="mp-welcome-header">' . wfMessage( 'mp-welcome-logged-in', $wgUser->getName() )->parse() . '</div>
		<div class="mp-welcome-image">
		<a href="' . htmlspecialchars( $wgUser->getUserPage()->getFullURL() ) . '" rel="nofollow">' .
			$avatar->getAvatarURL() . '</a>';
		if ( $wgUser->isLoggedIn() ) {
			$links = [];

			if ( $avatar->isDefault() ) {
				$uploadOrEditMsg = 'mp-welcome-upload';
				$links[] = '<a href="' . htmlspecialchars( $avatar_link->getFullURL() ) . '" rel="nofollow">' .
					wfMessage( $uploadOrEditMsg )->plain() .
				'</a>';
			} else {
				$uploadOrEditMsg = 'edit';
				$links[] = '<a href="' . htmlspecialchars( $avatar_link->getFullURL() ) . '" rel="nofollow">' .
					wfMessage( $uploadOrEditMsg )->plain() .
				'</a>';

				if ( $wgUser->isAllowed( 'avatarremove' ) ) {
					$removeAvatarURL = SpecialPage::getTitleFor( 'RemoveAvatar', $wgUser->getName() )->getFullURL();
				} else {
					$removeAvatarURL = SpecialPage::getTitleFor( 'RemoveAvatar' )->getFullURL();
				}
				$links[] = '<a href="' . htmlspecialchars( $removeAvatarURL ) . '" rel="nofollow">' .
					wfMessage( 'user-profile-remove-avatar' )->text() . '</a>';
			}

			$output .= '<div>';
			$output .= $wgLang->pipeList( $links );
			$output .= '</div>';
		}
		$output .= '</div>';

		global $wgUserLevels;
		if ( $wgUserLevels ) {
			$output .= '<div class="mp-welcome-points">
				<div class="points-and-level">
					<div class="total-points">' .
						wfMessage(
							'mp-welcome-points',
							$wgLang->formatNum( $stats_data['points'] )
						)->parse() . '</div>
					<div class="honorific-level"><a href="' . htmlspecialchars( $level_link->getFullURL() ) .
						'">(' . $user_level->getLevelName() . ')</a></div>
				</div>
				<div class="visualClear"></div>
				<div class="needed-points">
					<br />'
					. wfMessage(
						'mp-welcome-needed-points',
						htmlspecialchars( $level_link->getFullURL() ),
						$user_level->getNextLevelName(),
						$wgLang->formatNum( $user_level->getPointsNeededToAdvance() )
					)->text() .
				'</div>
			</div>';
		}

		$output .= '<div class="visualClear"></div>';
		$output .= $this->getRequests();
		$output .= '</div>';

		return str_replace(array("\r", "\n"), array("", ""), $output);
	}

	function getRequests() {
		// Get requests
		$requests = $this->getNewMessagesLink() . $this->getRelationshipRequestLink() .
					$this->getNewGiftLink() . $this->getNewSystemGiftLink();

		$output = '<div class="mp-requests">';
		if ( $requests ) {
			$output .= '<h3>' . wfMessage( 'mp-requests-title' )->plain() . '</h3>
			<div class="mp-requests-message">
				' . wfMessage( 'mp-requests-message' )->plain() . "
			</div>
			$requests";
		} else {
			$output .= '<div class="mp-requests-message mp-no-requests-message">
				' . wfMessage( 'mp-no-requests-message' )->plain() . "
			</div>";
		}

		return $output;
	}

	function getRelationshipRequestLink() {
		global $wgUser, $wgMemc;

		$requestCount = new RelationshipRequestCount( $wgMemc, $wgUser->getId() );
		$friendRequestCount = $requestCount->setFriends()->get();
		$foeRequestCount = $requestCount->setFoes()->get();

		$relationship_request_link = SpecialPage::getTitleFor( 'ViewRelationshipRequests' );

		$output = '';

		if ( $friendRequestCount > 0 ) {
			$userFriendIcon = new UserActivityIcon( 'friend' );
			$friendIcon = $userFriendIcon->getIconHTML();

			$output .= '<p>' . $friendIcon .
				'<span class="profile-on"><a href="' . htmlspecialchars( $relationship_request_link->getFullURL() ) . '" rel="nofollow">'
				. wfMessage( 'mp-request-new-friend', $friendRequestCount )->parse() . '</a></span>
			</p>';
		}

		if ( $foeRequestCount > 0 ) {
			$userFoeIcon = new UserActivityIcon( 'foe' );
			$foeIcon = $userFoeIcon->getIconHTML();

			$output .= '<p>' . $foeIcon .
				'<span class="profile-on"><a href="' . htmlspecialchars( $relationship_request_link->getFullURL() ) . '" rel="nofollow">'
				. wfMessage( 'mp-request-new-foe', $foeRequestCount )->parse() . '</a></span>
			</p>';
		}

		return $output;
	}

	function getNewGiftLink() {
		global $wgUser, $wgMemc;

		$userGiftCount = new UserGiftCount( $wgMemc, $wgUser->getId() );
		$giftCount = $userGiftCount->get();

		$gifts_title = SpecialPage::getTitleFor( 'ViewGifts' );
		$output = '';

		if ( $giftCount > 0 ) {
			$userActivityIcon = new UserActivityIcon( 'gift_rec' );
			$icon = $userActivityIcon->getIconHTML();

			$output .= '<p>' . $icon .
				'<span class="profile-on"><a href="' . htmlspecialchars( $gifts_title->getFullURL() ) . '" rel="nofollow">'
					. wfMessage( 'mp-request-new-gift', $giftCount )->parse() .
				'</a></span>
			</p>';
		}

		return $output;
	}

	function getNewSystemGiftLink() {
		global $wgUser, $wgMemc;

		$systemGiftCount = new SystemGiftCount( $wgMemc, $wgUser->getId() );
		$giftCount = $systemGiftCount->get();

		$gifts_title = SpecialPage::getTitleFor( 'ViewSystemGifts' );
		$output = '';

		if ( $giftCount > 0 ) {
			$userActivityIcon = new UserActivityIcon( 'system_gift' );
			$icon = $userActivityIcon->getIconHTML();

			$output .= '<p>' . $icon .
				'<span class="profile-on"><a href="' . htmlspecialchars( $gifts_title->getFullURL() ) . '" rel="nofollow">'
					. wfMessage( 'mp-request-new-award', $giftCount )->parse() .
				'</a></span>
			</p>';
		}

		return $output;
	}

	function getNewMessagesLink() {
		global $wgUser, $wgMemc;

		$messageCount = new UserBoardMessageCount( $wgMemc, $wgUser->getId() );
		$newMessages = $messageCount->get();
		$output = '';

		if ( $newMessages > 0 ) {
			$userActivityIcon = new UserActivityIcon( 'user_message' );
			$icon = $userActivityIcon->getIconHTML();

			$board_link = SpecialPage::getTitleFor( 'UserBoard' );
			$output .= '<p>' . $icon .
				'<span class="profile-on"><a href="' . htmlspecialchars( $board_link->getFullURL() ) . '" rel="nofollow">'
					. wfMessage( 'mp-request-new-message' )->plain() .
				'</a></span>
			</p>';
		}

		return $output;
	}
}
