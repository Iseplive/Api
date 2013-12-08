<?php
/**
 * Configuration of the routes
 */

final class Routes extends RoutesAbstract {
	
	/**
	 * List of the routes
	 *
	 *	{
	 *		"module" : {	// Name of the route. e.g. : 'user_view'
	 *			regexp : regular expression (PCRE) matching with the url. e.g. : '^user/([a-z0-9]+)/(?=\?|$)'
	 *			vars : variables corresponding to the url, with values of the previous regexp. e.g. : 'controller=User&action=view&username=$1'
	 *			url : URL of the page depending on various parameters ({id}, {title}...). e.g. : 'users/{username}/'
	 *			extend (optionnal) : {
	 *				"vars1" : "module_extended1"
	 *				"vars2" : "module_extended2"
	 *				...
	 *				// vars1 = names of additional variables (seperated by &). e.g. : 'page'
	 *				// module_extended1 = route name to use when these variables are defined : 'user_view_page'
	 *			}
	 *		},
	 *	...}
	 *
	 * @static array
	 */
	protected static $routes =	array(
    // Home
    'posts' => array(
        'regexp' => '^posts/([1-9][0-9]*)(?=\?|$)',
        'vars'   => 'controller=Post&action=index_ajax&page=$1&method=GET',
        'url'    => 'posts/{page}'
    ),
    // Post
		'post'	=> array(
			'regexp'	=> '^post/([0-9]+)(?=\?|$)',
			'vars'		=> 'controller=Post&action=view&id=$1',
			'url'		  => 'post/{id}'
		),
      // Sign-in
		'signin'	  => array(
			'regexp'	=> '^signin$',
			'vars'		=> 'controller=User&action=signin&method=POST&isPublic=true',
			'url'		  => 'signin',
		),
    // User profile
    'profil'	  => array(
			'regexp'	=> '^profil',
			'vars'		=> 'controller=Student&action=view&method=GET',
			'url'		  => 'profil'
		),
     // Student profile
    'student'	  => array(
			'regexp'	=> '^student/([a-z0-9-]+)(?=\?|$)$',
			'vars'		=> 'controller=Student&action=view&username=$1&method=GET',
			'url'		  => 'student/{username}'
		),
    // Students' promo list
		'students'	=> array(
			'regexp'	=> '^students/((\d{4})|(all))(?=\?|$)',
			'vars'		=> 'controller=Student&action=index&promo=$1&method=GET',
			'url'     => 'students/{promo}'
		),
    // log out
		'logout'	  => array(
			'regexp'	=> '^logout$',
			'vars'		=> 'controller=User&action=logout&method=GET&isPublic=true',
			'url'		  => 'logout'
		),
    // Group's page
    'group'	=> array(
			'regexp'	=> '^association/([a-z0-9-]+)(?=\?|$)',
			'vars'		=> 'controller=Group&action=view&group=$1',
			'url'     => 'association/{group}'
		),
    'groups'	=> array(
			'regexp'	=> '^associations(?=\?|$)',
			'vars'		=> 'controller=Group&action=index',
			'url'   	=> 'associations'
		),
    //Media's Liste
		'media'	=> array(
			'regexp'	=> '^media(?=\?|$)',
			'vars'		=> 'controller=Media&action=index',
			'url'		  => 'media'
		),
	);

}
