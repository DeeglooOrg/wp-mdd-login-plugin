<?php 

namespace PluginTest\Base;
use \Firebase\JWT\JWT;
use \Brain\Monkey\Functions;
use \Inc\Base\RequestHandler;
use \Inc\Base\URLRegistry;
use \Inc\Base\UserManager;

class RequestHandlerTest extends \PluginTestCase {
	public function tearDown() {
			\Mockery::close();
	}

  public function test_unauthorizedUserShouldNotBeCreated() {
		// Arrange
		$_SERVER['REQUEST_URI'] = 'https://www.test.test/' . URLRegistry::UNAUTHORIZED_ACCESS_LABEL;
		
		Functions\expect( 'plugin_dir_path' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_dir_url' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_basename' )
			->once()
			->andReturn('./');
		
		$stub = $this->getMockBuilder( RequestHandler::class )
			->setMethods(array('mockExit', 'loadUnauthorizedAccess'))
			->getMock();
		$stub->expects($this->once())
		->method('loadUnauthorizedAccess');
				
		// Assert
		$stub->mdd_login_url_handler();
	}

	public function test_unauthenticatedUserShouldNotBeCreated() {
		// Arrange
		$_SERVER['REQUEST_URI'] = 'https://www.test.test/' . URLRegistry::UNAUTHENTICATED_ACCESS_LABEL;
		
		Functions\expect( 'plugin_dir_path' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_dir_url' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_basename' )
			->once()
			->andReturn('./');
		
		$stub = $this->getMockBuilder( RequestHandler::class )
			->setMethods(array('mockExit', 'loadInvalidCredentials'))
			->getMock();
		$stub->expects($this->once())
		->method('loadInvalidCredentials');
				
		// Assert
		$stub->mdd_login_url_handler();
	}

	public function test_ssoLoginParserSouldBeRedirectedToSsoLoginPage() {
		// Arrange
		$_SERVER['REQUEST_URI'] = 'https://www.test.test/' . URLRegistry::SSO_LOGIN_LABEL;
		
		Functions\expect( 'plugin_dir_path' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_dir_url' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_basename' )
			->once()
			->andReturn('./');
		
		$stub = $this->getMockBuilder( RequestHandler::class )
			->setMethods(array('mockExit', 'loadSsoLogin'))
			->getMock();
		$stub->expects($this->once())
		->method('loadSsoLogin');
				
		// Assert
		$stub->mdd_login_url_handler();
	}

	public function test_userWithTokenButForPluginConfigPageNoLogin() {
		// Arrange
		$user_data = (object) array(
			'user_name' => 'test@test.test',
			'email' => 'test@test.test',
			'user_name' => 'test@test.test',
			'permissions' => array('role1', 'role2')
		);
		$client_secret = 'secret';
		$token = JWT::encode($user_data, $client_secret);
		$_SERVER['REQUEST_URI'] = 'https://www.test.test/' . URLRegistry::PARSE_TOKEN_LABEL;
		$_SERVER['QUERY_STRING'] = 'access_token=' . $token . '&state=config_test';
		
		Functions\expect( 'plugin_dir_path' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_dir_url' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_basename' )
			->once()
			->andReturn('./');
		
		$stub = $this->getMockBuilder( RequestHandler::class )
			->setMethods(array('mockExit', 'loadTokenProcessing'))
			->getMock();
		$stub->expects($this->once())
		->method('loadTokenProcessing');
				
		// Assert
		$stub->mdd_login_url_handler();
	}

	public function test_onTokenReceivedUserShouldBeCreated() {
		// Arrange
		$user_data = (object) array(
			'user_name' => 'test@test.test',
			'email' => 'test@test.test',
			'user_name' => 'test@test.test',
			'permissions' => array('role1', 'role2')
		);
		$client_secret = 'secret';
		$token = JWT::encode($user_data, $client_secret);
		$_SERVER['REQUEST_URI'] = 'https://www.test.test/' . URLRegistry::PARSE_TOKEN_LABEL;
		$_SERVER['QUERY_STRING'] = 'access_token=' . $token;
		
		$roles = \Mockery::mock('\WP_Roles');
		$roles->roles = array(
			'ADMIN' => $user_data->permissions[0],
			'USER' => $user_data->permissions[1] 
		);

		// Prepare
		$userManager = \Mockery::mock('UserManager');
		$userManager->shouldReceive('userExists')
			->with($user_data->email)
			->times(1)
			->andReturn(false);
			$userManager->shouldReceive('createUser')
			->with($user_data->user_name, \Mockery::any(), $user_data->email, array('ADMIN', 'USER'))
			->times(1);
			$userManager->shouldReceive('updateRolesForUserWithEmail')
			->times(0);
		$userManager->shouldReceive('loginAsUser')
			->with($user_data->email)
			->times(1);

		Functions\expect( 'wp_roles' )
			->once()
			->andReturn($roles);
		Functions\expect( 'esc_attr' )
			->times(6)
			->andReturnUsing(function($arg) {
				return $arg;
			});
		Functions\expect( 'plugin_dir_path' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_dir_url' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_basename' )
			->once()
			->andReturn('./');
		Functions\expect( 'get_option' )
			->with('authorities_field')
			->once()
			->andReturn('permissions');
		Functions\expect( 'get_option' )
			->with('client_secret')
			->once()
			->andReturn($client_secret);
		Functions\expect( 'get_option' )
			->with('email_field')
			->once()
			->andReturn('email');
		Functions\expect( 'get_option' )
			->with('username_field')
			->once()
			->andReturn('user_name');
		Functions\expect( 'get_option' )
			->with('mdd_role_ADMIN')
			->once()
			->andReturn($user_data->permissions[0]);
		Functions\expect( 'get_option' )
			->with('mdd_role_USER')
			->once()
			->andReturn($user_data->permissions[1]);
		
		$stub = $this->getMockBuilder( RequestHandler::class )
			->setMethods(array('mockExit', 'getUserManager'))
			->getMock();
		$stub->expects($this->once())
			->method('getUserManager')
			->will($this->returnValue($userManager));
				
		// Assert
		$stub->mdd_login_url_handler();
	}

	public function test_onTokenReceivedUserShouldNotBeCreatedIfExists() {
		// Arrange
		$user_data = (object) array(
			'user_name' => 'test@test.test',
			'email' => 'test@test.test',
			'user_name' => 'test@test.test',
			'permissions' => array('role1', 'role2')
		);
		$client_secret = 'secret';
		$token = JWT::encode($user_data, $client_secret);
		$_SERVER['REQUEST_URI'] = 'https://www.test.test/' . URLRegistry::PARSE_TOKEN_LABEL;
		$_SERVER['QUERY_STRING'] = 'access_token=' . $token;
		
		$roles = \Mockery::mock('\WP_Roles');
		$roles->roles = array(
			'ADMIN' => $user_data->permissions[0],
			'USER' => $user_data->permissions[1] 
		);

		// Prepare
		$userManager = \Mockery::mock('UserManager');
		$userManager->shouldReceive('userExists')
			->with($user_data->email)
			->times(1)
			->andReturn(true);
			$userManager->shouldReceive('createUser')
			->with($user_data->user_name, \Mockery::any(), $user_data->email, array('ADMIN', 'USER'))
			->times(0);
			$userManager->shouldReceive('updateRolesForUserWithEmail')
			->times(1);
		$userManager->shouldReceive('loginAsUser')
			->with($user_data->email)
			->times(1);

		Functions\expect( 'wp_roles' )
			->once()
			->andReturn($roles);
		Functions\expect( 'esc_attr' )
			->times(6)
			->andReturnUsing(function($arg) {
				return $arg;
			});
		Functions\expect( 'plugin_dir_path' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_dir_url' )
			->once()
			->andReturn('./');
		Functions\expect( 'plugin_basename' )
			->once()
			->andReturn('./');
		Functions\expect( 'get_option' )
			->with('authorities_field')
			->once()
			->andReturn('permissions');
		Functions\expect( 'get_option' )
			->with('client_secret')
			->once()
			->andReturn($client_secret);
		Functions\expect( 'get_option' )
			->with('email_field')
			->once()
			->andReturn('email');
		Functions\expect( 'get_option' )
			->with('username_field')
			->once()
			->andReturn('user_name');
		Functions\expect( 'get_option' )
			->with('mdd_role_ADMIN')
			->once()
			->andReturn($user_data->permissions[0]);
		Functions\expect( 'get_option' )
			->with('mdd_role_USER')
			->once()
			->andReturn($user_data->permissions[1]);
		
		$stub = $this->getMockBuilder( RequestHandler::class )
			->setMethods(array('mockExit', 'getUserManager'))
			->getMock();
		$stub->expects($this->once())
			->method('getUserManager')
			->will($this->returnValue($userManager));
		
		// Assert
		$stub->mdd_login_url_handler();
	}

	
}
