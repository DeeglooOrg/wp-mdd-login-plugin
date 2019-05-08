<?php 

namespace PluginTest\Stuff;
use \Plugin\Stuff\SomeClass;
use \Brain\Monkey\Functions;
use Inc\Base\UserManager;

class UserManagerTest extends \PluginTestCase {
	public function test_userExistsShouldReturnTrue() {
		// Arrange
		$user_email = 'admin@test.test';

		Functions\expect( 'email_exists' )
			->once()
			->with( $user_email )
			->andReturn(true);
		
		$stub = $this->getMockForAbstractClass( UserManager::class );
		$stub_class = get_class( $stub );
		
		// Assert
		$this->assertEquals( true, $stub->userExists($user_email) );
	}

	public function test_userExistsShouldReturnFalse() {
		// Arrange
		$user_email = 'admin@test.test';

		Functions\expect( 'email_exists' )
			->once()
			->with( $user_email )
			->andReturn(false);
		
		$stub = $this->getMockForAbstractClass( UserManager::class );
		$stub_class = get_class( $stub );
		
		// Assert
		$this->assertEquals( false, $stub->userExists($user_email) );
	}

	public function test_loginAsUserIfUserExistsShouldRedirectToSite() {
		// Arrange
		$user_email = 'admin@test.test';
		$user = \Mockery::mock('\WP_User');
		$user->ID = 1;
		$site_url = 'http://test.test/';

		Functions\expect( 'get_user_by' )
			->once()
			->with( 'email', $user_email )
			->andReturn($user);

		Functions\expect( 'email_exists' )
			->once()
			->with( $user_email )
			->andReturn(true);

		Functions\stubs([
			'wp_clear_auth_cookie' => true,
			'wp_set_current_user' => true,
			'wp_set_auth_cookie' => true,
			'get_site_url' => $site_url
		]);

		Functions\expect( 'wp_safe_redirect' )
			->once()
			->with( $site_url )
			->andReturn(true);
		
		$stub = $this->getMockForAbstractClass( UserManager::class );
		$stub_class = get_class( $stub );
		
		// Assert
		$stub->loginAsUser($user_email);
	}

	public function test_userExistsShouldNotRedirectToSite() {
			// Arrange
			$user_email = 'admin@test.test';
			$site_url = 'http://test.test/';
			$error = \Mockery::mock('\WP_Error') ;

			Functions\expect( 'wp_login_url' )
				->once()
				->andReturn($site_url);

			Functions\expect( 'get_user_by' )
				->never();

			Functions\expect( 'email_exists' )
				->once()
				->with( $user_email )
				->andReturn(false);
				
			Functions\expect( 'wp_clear_auth_cookie' )
				->never();
			Functions\expect( 'wp_set_current_user' )
				->never();
			Functions\expect( 'wp_set_auth_cookie' )
				->never();
			Functions\expect( 'get_site_url' )
				->never();
			Functions\expect( 'wp_redirect' )
				->once();
			
			$stub = $this->getMockForAbstractClass( UserManager::class );
			$stub_class = get_class( $stub );
			
			// Assert
			$stub->loginAsUser($user_email);
	}

	public function test_createUser() {
		// Arrange
		$username = 'admin';
		$password = 'password';
		$email = 'admin@test.test';
		$roles = ['role1', 'role2'];
		$user = \Mockery::mock('\WP_User');
		$user->shouldReceive('set_role')
			->with($roles[0])
			->times(1);
		$user->shouldReceive('add_role')
			->with($roles[1])
			->times(1);
		$user->ID = 1;

		Functions\expect( 'wp_create_user' )
			->once()
			->with( $username, $password, $email)
			->andReturn($user->ID);

		Functions\expect( 'get_user_by' )
			->once()
			->with( 'email', $email )
			->andReturn($user);

		
		$stub = $this->getMockForAbstractClass( UserManager::class );
		$stub_class = get_class( $stub );
		
		// Assert
		$stub->createUser($username, $password, $email, $roles);
	}

	public function test_updateRolesForRegistratedUser() {
		// Arrange
		$email = 'admin@test.test';
		$roles = ['role1', 'role2'];
		$user = \Mockery::mock('\WP_User');
		$user->shouldReceive('set_role')
			->with($roles[0])
			->times(1);
		$user->shouldReceive('add_role')
			->with($roles[1])
			->times(1);
		$user->ID = 1;

		Functions\expect( 'get_user_by' )
			->once()
			->with( 'email', $email )
			->andReturn($user);

		
		$stub = $this->getMockForAbstractClass( UserManager::class );
		$stub_class = get_class( $stub );
		
		// Assert
		$stub->updateRolesForUserWithEmail($email, $roles);
	}

	public function test_updateRolesForNonRegistratedUser() {
		// Arrange
		$email = 'admin@test.test';
		$roles = ['role1', 'role2'];
		$user = \Mockery::mock('\WP_User');
		$user->shouldReceive('set_role')
			->never();
		$user->shouldReceive('add_role')
			->never(1);
		$user->ID = 1;

		Functions\expect( 'get_user_by' )
			->once()
			->with( 'email', $email )
			->andReturn(false);

		
		$stub = $this->getMockForAbstractClass( UserManager::class );
		$stub_class = get_class( $stub );
		
		// Assert
		$stub->updateRolesForUserWithEmail($email, $roles);
	}
}
?>
