<?php

/*
|--------------------------------------------------------------------------
| Load The Cached Routes
|--------------------------------------------------------------------------
|
| Here we will decode and unserialize the RouteCollection instance that
| holds all of the route information for an application. This allows
| us to instantaneously load the entire route map into the router.
|
*/

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BNw1Usq1m6BYQ2mq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::P7vSb4li6KqzjxIw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/qr' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'qr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getDriversLocation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'getDriversLocation',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driver-arrived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverarrived',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/info' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'info.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/pincodeDetails' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodeDetails',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/readBy' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.readBy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/redDot' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'redDot',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/userToken' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userToken',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverToken' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverToken',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/automaticBookingCancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.automaticBookingCancel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/automaticBookingComplete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.automaticBookingComplete',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/maintainance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'maintainance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/coupons' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.coupons',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/couponList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'couponList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appVerision' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'appVerision',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/reportDriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'reportDriver',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/paymentStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymentStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverDocument' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverDocument',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/activeRadius' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'activeRadius',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/checkIsRadius' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'checkIsRadius',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getBookingViaLocation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'getBookingViaLocation',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/googleSignIn' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'googleSignIn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/googleSignUp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'googleSignUp',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/drivercurrentlocation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'drivercurrentlocation',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment_status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payment_status',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/currentStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'currentStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/availablePincode' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'availablePincode',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/favouriteAddressList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'favouriteAddressList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/addAddress' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'addAddress',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/userProfile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.userProfile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/forgotPassword' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.forgotPassword',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.register',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.login',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/sendMessage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sendMessage',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/reciveMessage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'reciveMessage',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/profiles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profileUser',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/profile/verify' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userVerify',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/booking/calculation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingCalculation',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/companies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'companies.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'companies.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/profile/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profileUpdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/category/list' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'categoryList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverlogin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverlogin',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverlogincheck' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverlogincheck',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/pincheck' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincheck',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/notificationtodriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'notificationtodriver',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/userprofile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userprofile',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverprofile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverprofile',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/drivercall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'drivercall',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/subscribercall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribercall',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/notificationtodriverfirebase' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'notificationtodriverfirebase',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingtaxi' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q2wUX33dhMmEBZ3f',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/rating' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'rating',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingtaxi1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WkeEEZa2P44cwvlu',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/bookingtaxi' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.bookingtaxi',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/bookinghistory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.bookinghistory',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/bookingdetailsoftheuser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.bookingdetailsoftheuser',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/getbookingdetailsofid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.getbookingdetailsofid',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/livetrackinguser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.livetrackinguser',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/locationuser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.locationuser',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/payment_status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.payment_status',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/external/paymentStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'external.paymentStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TVu6OThpCX8RtJsC',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getbookingdetailsofid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Xm0nSiBE7WfjBCQ6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getbookingdetails' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CK98QeRpkOxh1Bft',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getlivelocation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lJU21Sn1pdOckiDA',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/banner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::btE2qWyvixoHMNRN',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/voucher' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::H0olb8V6EM0OwVGD',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingaccept' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UWPlAnCrdws90twm',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingprovideraccept' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3LzntBEHcATW9hoS',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/booking_provider_pending_list' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EILOptPYGpJKeRnB',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/booking_provider_assign_driver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ibwFIpi1gZcLqauw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingignore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TvV6OsdgjcPm6VxN',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingcancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CKjFmEGR6yAKbpp0',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/userbookingcancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iMEIAdlnZwklaRU6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/userbookingcancelwithoutreason' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6FOtcZIttiGoSGKr',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/livetrackingdriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JhovcClyDlAMmILw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/livetrackinguser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8HA89sYN9j8klJUw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookingdetailsoftheuser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EEj5qGJbogoW8PVf',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverbookingcomplete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Xi1VCiw83LZwRrAa',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/locationuser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uhPcOl7OaB0pKsak',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/locationdriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AOxXsee3e1tJJLtk',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/checkavailabledriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xklBVFCH9nv4J3xj',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/otpverification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jLcqlOCOnUMoJwVf',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/otpcompleteverification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xhBc8uQUN2nrcUnt',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/getstatusofbooking' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4SXGNJqueTyqa4Wd',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookinghistory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Ez7nNKJx7q3Noe2p',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/driverhistory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KmzaB1FzA2Dsk8DL',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/userBookingHistory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userBookingHistory',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/sendnotificationtootherdriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dOHXShUUGB6xV9Mf',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/drivertocustomercall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RZK1GdwbM5CgyNkB',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/checkNumber' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'checkNumber',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/generate_referral_code' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9xOUiDj3szMezcQF',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accountDeletion' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rXrMfy8f6dmwN2MB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/generate_referral_code' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LRqtgW23a18Y0eJZ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/zipcodeE' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'zipcodeE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/automaticBookingComplete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'web.automaticBookingComplete',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/automaticBookingCancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'web.automaticBookingCancel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tc' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::foQikCp6kRI786A2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/returnandrefundpolicy' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::y8WwitkY9jow1HeY',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/privacypolicyandcookies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Kb06RPP5c70io4QQ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dcKNuxijR5dBfL8J',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VBmVlvT2NtouqGJ6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::99cvMJwbHqjacRf0',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/password/reset' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/password/email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/password/confirm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HkIgRUDi0NH2TtVh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/usedPincodes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'usedPincodes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pincodebasedcategory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pincodebasedcategory/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pincodebasedcategorystatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.pincodebasedcategorystatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/makePincodeBasedCategory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'makePincodeBasedCategory',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/home' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/createpincode' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'createpincode',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pincode' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincode',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pincodestore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xuxZCZX99zDxIFNm',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pincode/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincode.search',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/search-pincode' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'search.pincode',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/createSubscriber' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'createSubscriber',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberList/self-registered-subscribers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.withoutEmployeeId',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::W9Lmn1ite3LC4IWM',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriber/onboarding/send-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.onboarding.otp.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriber/onboarding/verify-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.onboarding.otp.verify',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriber/verify-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.otp.form',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.otp.verify',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriber/resend-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.otp.resend',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/changeStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::GPOcPDArP7VtjEmk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expiredsubscriber' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'expiredsubscriber',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberunblock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::i4rfeXIIRUv89wXy',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/enduser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enduser',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/category' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'category',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/categoryStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PhPgnHEdeVul93cM',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'drivers',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driver/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driver.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driverstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YHopm2wl9q8prSSc',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driverActivate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::I1x6x0tNfmJKby7O',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driverNotification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uXsr7TeAKCnvub9W',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dot-notify' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dot-notify',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driverunblock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tBf5UmFbMZLpf91I',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberblockList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberblockList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/adminBlocked' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'adminBlockeddriver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driverblockList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverblockList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberunblockList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberunblockList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/adminUnblocked' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'adminUnblockeddriver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/driverunblockList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverunblockList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coupons' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.coupons',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coupons/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.createcoupons',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/couponsstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::s5OIPIeE9LbWTTur',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/couponsActivate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::isLL7UB6wnWsdKgX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/priceNotification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'priceNotification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberNotification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberNotification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/markread' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U6GFNj1MDXZgYj8O',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/markasread' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3lJtm8mpeCkZmCVI',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/enquiry' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/enquiry/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/excelpincode' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/excelpincode/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/complaints' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/complaints/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'role.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/role/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/users/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/messageEmployee' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'messageEmployee',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberLogin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/loginaction' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dwv1Qsk8I9QT1j2g',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/roles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'roles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/roles/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/subPermission' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wlMRBfA5NvYB8FFX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/subEmployee' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/subEmployee/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/pincodes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodes.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/pincodebasedcategorystatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.pincodebasedcategorystatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberForgotPassword' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberForgotPassword',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberEmailVerification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberEmailVerification',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberConfimed_password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberConfimed_password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/createDriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'createdriver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/driver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/driverstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::52VZHmihM3XfyLJN',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/driverStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DbnYS6rL8QjeMwDp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/blockedDriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blockedDriver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/driverunblock' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NuOIG1rQ2N7cn29g',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/updateProfile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bBihmcYI5YOa9uhR',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/price' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.price',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/pricestore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zqkkCspYvngrclgK',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/coupons' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupons',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/coupons/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.createcoupons',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/couponsstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WngXOIwfKhwzMs2P',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/couponsActivate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hlGCY8omj3gOdlWT',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/complaints' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.complaints',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/complaints/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.complaintsform',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/complaintsstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.complaintsstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/enquiry' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.enquiry',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/enquiry/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.enquiryform',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/enquirysstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.enquirystore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/bookingReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/bookingReport/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/downloadsExcel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadsExcel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/downloadsPDF' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadsPDF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/driverReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverReport.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/coupon' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupon.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupon.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscribers/coupon/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupon.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oNOFIOARHFIYenpe',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/about' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yGJ3xTNueymltYpf',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HlyfraW0R7QN6eVE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/contact' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5MXh4FLPwoaeQhVj',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pbp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EtoQKgBEVtop9bV5',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/inquirystore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'inquirystore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/feedback' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'feedback',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/storeNewsletter' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storeNewsletter',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zxUigAtBW3m0UpPf',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4trHfPg9SAWINaUm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/siteupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'siteupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/slider' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZM1SsiHIpwyJur1x',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/siteupdateimage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'siteupdateimage',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/pushnotification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pushnotification.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pushnotification.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/forgotPassword' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'forgotPassword',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/emailVerification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'emailVerification',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/confimed_password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'confimed_password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/newsletter' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'newsletter',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/newsLetterExcel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'newsLetterExcel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/newsLetterPDF' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'newsLetterPDF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/statusactive' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'statusactive',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/statusonoroff' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lJPBSNZl5cpGKGtp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberstatuschange' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberstatuschange',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/makepayment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'makepayment',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/validId' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'validId',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/successpay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'successpay',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chatwithadmin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chatwithadmin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberchatwithadmin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberchatwithadmin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/rl' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1MfuiIpqXa6Xnu4S',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chatsupport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CQgmIP8qScrtufZc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/Banner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YZoXldre0uhblPbt',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/bannersubmit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bannersubmit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/bannereditsubmit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bannereditsubmit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/Voucher' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QEZ9zvfZ5pA2BVN0',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/vouchersubmit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vouchersubmit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/vouchereditsubmit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vouchereditsubmit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/bookingReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/downloadExcel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadExcel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/downloadPDF' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadPDF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberExpiry' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberExpiry',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expirydownloadExcel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'expirydownloadExcel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/expirydownloadPDF' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'expirydownloadPDF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberReport.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/checking' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'checking.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/employeeReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeeReport.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/employeePerformance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeePerformance.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/enquiryComment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enquiryComment.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/feed' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'feed.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/report' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'report.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getchartdata' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'getChartData',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/paymenthistory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/paymenthistory/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/paymentReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/paymentReport/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/voucher' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/voucher/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coupon' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coupon/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coupStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coupStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/banner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.banner.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.banner.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/banner/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.banner.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/info' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.info.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.info.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/info/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.info.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/infoStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.infoStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/companies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/companies/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subscriberWhatsappNotify' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberWhatsappNotify',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/makePlatFormFee' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'makePlatFormFee',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getAccessTokenUser' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'getAccessTokenUser',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getAccessTokenDriver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'getAccessTokenDriver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/a(?|pi/(?|deleteAddress/([^/]++)(*:40)|u(?|pdate(?|Profile/([^/]++)(*:75)|MobileNumber/([^/]++)(*:103))|ser/filter/([^/]++)(*:131))|booking/([^/]++)(*:156)|companies/([^/]++)(?|(*:185)))|dmin/(?|delete/([^/]++)(*:218)|banner/([^/]++)(?|/edit(*:249)|(*:257))|info/([^/]++)(?|/edit(*:287)|(*:295))|companies/([^/]++)(?|(*:325)|/(?|edit(*:341)|toggle\\-status(*:363))|(*:372))))|/p(?|a(?|ssword(?|/reset/([^/]++)(*:416)|Reset/([^/]++)(*:438))|yment(?|history/([^/]++)(?|(*:474)|/edit(*:487)|(*:495))|Report/([^/]++)(?|(*:522)|/edit(*:535)|(*:543))))|incode(?|basedcategory/([^/]++)(?|(*:588)|/edit(*:601)|(*:609))|/([^/]++)(*:627)|update/([^/]++)(*:650)|delete/([^/]++)(*:673))|rofile/([^/]++)(?|/edit(*:705)|(*:713)))|/su(?|bscriber(?|/(?|show/([0-9]+)(*:757)|([0-9]+)(*:773))|update/([0-9]+)(*:797)|delete/([0-9]+)(*:820)|block/([^/]++)(*:842)|s/(?|roles/([^/]++)(?|(*:872)|/edit(*:885)|(*:893))|subEmployee/([^/]++)(?|(*:925)|/edit(*:938)|(*:946))|pincodes/([^/]++)(*:972)|driver(?|/(?|show/([^/]++)(*:1006)|([^/]++)(*:1023))|update/([^/]++)(*:1048)|delete/([^/]++)(*:1072)|block/([^/]++)(*:1095)|Report/([^/]++)(*:1119)|ExcelDownload/([^/]++)(*:1150)|PdfDownload/([^/]++)(*:1179))|co(?|upon(?|s(?|/([^/]++)(*:1214)|update/([^/]++)(*:1238)|delete/([^/]++)(*:1262))|/([^/]++)(?|(*:1284)))|mplaint(?|s/([^/]++)(*:1315)|Edit/([^/]++)(*:1337)|Update/([^/]++)(*:1361)|Action/([^/]++)(*:1385)))|enquiry/([^/]++)(*:1412)|bookingReport/([^/]++)(?|(*:1446)|/edit(*:1460)|(*:1469)))|passwordReset/([^/]++)(*:1502)|Report/([^/]++)(*:1526))|ccessful(?|lypayment/([^/]++)(*:1565)|PlatFormFee/([^/]++)(*:1594)))|/d(?|eletesubscribervideo/([^/]++)(*:1639)|river(?|/(?|show/([^/]++)(*:1673)|([^/]++)(*:1690))|update/([^/]++)(*:1715)|block/([^/]++)(*:1738)))|/b(?|lockenduser/([^/]++)(*:1774)|annerdelete/([^/]++)(*:1803)|ookingReport/([^/]++)(*:1833))|/u(?|nblockenduser/([^/]++)(*:1870)|sers/([^/]++)(?|(*:1895)|/edit(*:1909)|(*:1918))|pdateAppVerision/([^/]++)(*:1953))|/r(?|ead(?|By/([^/]++)(*:1985)|more/([^/]++)(*:2007))|ole/([^/]++)(?|(*:2032)|/edit(*:2046)|(*:2055)))|/c(?|o(?|upon(?|s(?|/([^/]++)(*:2095)|update/([^/]++)(*:2119)|delete/([^/]++)(*:2143))|/([^/]++)(?|(*:2165)|/edit(*:2179)|(*:2188)))|mplaint(?|s/([^/]++)(?|(*:2222)|/edit(*:2236)|(*:2245))|/([^/]++)(*:2264)|Report/([^/]++)(*:2288)|Excel/([^/]++)(*:2311)|Pdf/([^/]++)(*:2332)))|h(?|at/([^/]++)(*:2358)|ecking/([^/]++)(*:2382))|rypto/(?|settings/([^/]++)(?|/edit(*:2426)|(*:2435))|add\\-coins/([^/]++)(*:2464)))|/e(?|nquiry/([^/]++)(?|(*:2498)|/edit(*:2512)|(*:2521))|xcelpincode/([^/]++)(?|(*:2554)|/edit(*:2568)|(*:2577))|mployee(?|Report/([^/]++)(*:2612)|Excel/([^/]++)(*:2635)|P(?|df/([^/]++)(*:2659)|erformance(?|/([^/]++)(*:2690)|Excel/([^/]++)(*:2713)|Pdf/([^/]++)(*:2734)))))|/voucher(?|delete/([^/]++)(*:2773)|/([^/]++)(?|(*:2794)|/edit(*:2808)|(*:2817)))|/Subscriberdownload(?|Excel/([^/]++)(*:2864)|Pdf/([^/]++)(*:2885))|/feed/([^/]++)(*:2909)|/invoicedownloadPDF/([^/]++)(*:2946)|/getAppVerision/([^/]++)(*:2979))/?$}sDu',
    ),
    3 => 
    array (
      40 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'deleteAddress',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      75 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'updateProfile',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      103 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'updateMobileNumber',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      131 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userFilter',
          ),
          1 => 
          array (
            0 => 'action',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      156 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'index',
          ),
          1 => 
          array (
            0 => 'action',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      185 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'companies.show',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'companies.update',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'companies.destroy',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      218 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::o0VdhHsPwdw4QhsO',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      249 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.banner.edit',
          ),
          1 => 
          array (
            0 => 'banner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      257 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.banner.update',
          ),
          1 => 
          array (
            0 => 'banner',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.banner.destroy',
          ),
          1 => 
          array (
            0 => 'banner',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      287 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.info.edit',
          ),
          1 => 
          array (
            0 => 'info',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      295 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.info.update',
          ),
          1 => 
          array (
            0 => 'info',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.info.destroy',
          ),
          1 => 
          array (
            0 => 'info',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      325 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.show',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      341 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.edit',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      363 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.toggle-status',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      372 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.update',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.destroy',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      416 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      438 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passwordReset',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      474 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.show',
          ),
          1 => 
          array (
            0 => 'paymentDetail',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      487 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.edit',
          ),
          1 => 
          array (
            0 => 'paymentDetail',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      495 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.update',
          ),
          1 => 
          array (
            0 => 'paymentDetail',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'paymenthistory.destroy',
          ),
          1 => 
          array (
            0 => 'paymentDetail',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      522 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.show',
          ),
          1 => 
          array (
            0 => 'paymentReport',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      535 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.edit',
          ),
          1 => 
          array (
            0 => 'paymentReport',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      543 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.update',
          ),
          1 => 
          array (
            0 => 'paymentReport',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'paymentReport.destroy',
          ),
          1 => 
          array (
            0 => 'paymentReport',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      588 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.show',
          ),
          1 => 
          array (
            0 => 'pincodebasedcategory',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      601 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.edit',
          ),
          1 => 
          array (
            0 => 'pincodebasedcategory',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      609 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.update',
          ),
          1 => 
          array (
            0 => 'pincodebasedcategory',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pincodebasedcategory.destroy',
          ),
          1 => 
          array (
            0 => 'pincodebasedcategory',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      627 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8BUXXmqSLjSGjAwc',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      650 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lI9qc6TliUdmnBjO',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      673 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::67kysRy8CxWlNDAq',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      705 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.edit',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      713 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.update',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      757 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriber.show',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      773 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tDHLsAGxwxIcL9sE',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      797 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::etZ0IWdkpNkHgsy8',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      820 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mMfN4xErld2lJGxG',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      842 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UlrLzxGWJVSEZsFu',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      872 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.show',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      885 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.edit',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      893 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'roles.destroy',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      925 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.show',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      938 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.edit',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      946 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.update',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'subEmployee.destroy',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      972 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pincodes.show',
          ),
          1 => 
          array (
            0 => 'pincode',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.driver.show',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1023 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::o8WXDJphsFCwvoBP',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1048 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::i3m2wAekxxMwSKCN',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1072 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Sw0dJU6At3mlQxyu',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1095 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::APPdo6OBWEUNn9iR',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1119 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverReport.show',
          ),
          1 => 
          array (
            0 => 'subscriber',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1150 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverExcelDownload',
          ),
          1 => 
          array (
            0 => 'driver',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1179 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'driverPdfDownload',
          ),
          1 => 
          array (
            0 => 'driver',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1214 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yqGhRJqNQTukL9GQ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1238 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6tjmLTgsMe2moKD9',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1262 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ixMLbJ91KGXq8SAt',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1284 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupon.show',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupon.update',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'subscribers.coupon.destroy',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1315 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintShow',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1337 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintEdit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1361 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintUpdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1385 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintAction',
          ),
          1 => 
          array (
            0 => 'complaint',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1412 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::M1hCoEgXjVfVAHyg',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1446 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.show',
          ),
          1 => 
          array (
            0 => 'bookingReport',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1460 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.edit',
          ),
          1 => 
          array (
            0 => 'bookingReport',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1469 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.update',
          ),
          1 => 
          array (
            0 => 'bookingReport',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'bookingReport.destroy',
          ),
          1 => 
          array (
            0 => 'bookingReport',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1502 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberpasswordReset',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1526 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'subscriberReport.show',
          ),
          1 => 
          array (
            0 => 'subscriber',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1565 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'successfullypayment',
          ),
          1 => 
          array (
            0 => 'user_id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1594 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'successfulPlatFormFee',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1639 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7usEiP2uQpnIfth7',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1673 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.driver.show',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1690 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BbzymYIIvGI5XHrm',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1715 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tcSQ9VhX7Q3Ed79m',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1738 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nUP20CK9cV3AXq7x',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1774 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blockenduser',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1803 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bannerdelete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1833 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'viewbooking',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1870 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'unblockenduser',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1895 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1909 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1918 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.destroy',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1953 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'updateAppVerision',
          ),
          1 => 
          array (
            0 => 'site',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1985 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.readBy',
          ),
          1 => 
          array (
            0 => 'readBy',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2007 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lX1UItMeFdbR4HL0',
          ),
          1 => 
          array (
            0 => 'what',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2032 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.show',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2046 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.edit',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2055 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'role.destroy',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2095 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HEZVll0eupHyY16x',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2119 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LMiQ2rtfIRXukGui',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2143 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4ew0T13NIr0VV8Kx',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2165 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.show',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2179 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.edit',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2188 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.update',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'coupon.destroy',
          ),
          1 => 
          array (
            0 => 'coupon',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2222 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.show',
          ),
          1 => 
          array (
            0 => 'complaint',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2236 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.edit',
          ),
          1 => 
          array (
            0 => 'complaint',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2245 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.update',
          ),
          1 => 
          array (
            0 => 'complaint',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'complaints.destroy',
          ),
          1 => 
          array (
            0 => 'complaint',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2264 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaint',
          ),
          1 => 
          array (
            0 => 'complaint',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2288 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintReport.show',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2311 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintExcel',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2332 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'complaintPdf',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2358 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2382 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'checking.update',
          ),
          1 => 
          array (
            0 => 'checking',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2426 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'crypto.settings.edit',
          ),
          1 => 
          array (
            0 => 'site',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2435 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'crypto.settings.update',
          ),
          1 => 
          array (
            0 => 'site',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2464 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'crypto.addCoins',
          ),
          1 => 
          array (
            0 => 'site',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2498 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.show',
          ),
          1 => 
          array (
            0 => 'enquiry',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2512 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.edit',
          ),
          1 => 
          array (
            0 => 'enquiry',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2521 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.update',
          ),
          1 => 
          array (
            0 => 'enquiry',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'enquiry.destroy',
          ),
          1 => 
          array (
            0 => 'enquiry',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2554 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.show',
          ),
          1 => 
          array (
            0 => 'excelPincode',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2568 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.edit',
          ),
          1 => 
          array (
            0 => 'excelPincode',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2577 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.update',
          ),
          1 => 
          array (
            0 => 'excelPincode',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'excelpincode.destroy',
          ),
          1 => 
          array (
            0 => 'excelPincode',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2612 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeeReport.show',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2635 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeeExcel',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2659 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeePdf',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2690 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeePerformance.show',
          ),
          1 => 
          array (
            0 => 'admin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2713 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeePerformanceExcel',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2734 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'employeePerformancePdf',
          ),
          1 => 
          array (
            0 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2773 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'voucherdelete',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2794 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.show',
          ),
          1 => 
          array (
            0 => 'voucher',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2808 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.edit',
          ),
          1 => 
          array (
            0 => 'voucher',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2817 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.update',
          ),
          1 => 
          array (
            0 => 'voucher',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'voucher.destroy',
          ),
          1 => 
          array (
            0 => 'voucher',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2864 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'SubscriberdownloadExcel',
          ),
          1 => 
          array (
            0 => 'subscriber',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2885 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'SubscriberdownloadPdf',
          ),
          1 => 
          array (
            0 => 'subscriber',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2909 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'feed.show',
          ),
          1 => 
          array (
            0 => 'feedback',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2946 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicedownloadPDF',
          ),
          1 => 
          array (
            0 => 'paymentDetail',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2979 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'getAppVerision',
          ),
          1 => 
          array (
            0 => 'site',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'generated::BNw1Usq1m6BYQ2mq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'generated::BNw1Usq1m6BYQ2mq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::P7vSb4li6KqzjxIw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\CacheController@clear',
        'controller' => 'App\\Http\\Controllers\\CacheController@clear',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::P7vSb4li6KqzjxIw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'qr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/qr',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getQr',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getQr',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'qr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'getDriversLocation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/getDriversLocation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\User\\DriverLocationController@index',
        'controller' => 'App\\Http\\Controllers\\API\\User\\DriverLocationController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'getDriversLocation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverarrived' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/driver-arrived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverarrived',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverarrived',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverarrived',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'info.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/info',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'as' => 'info.index',
        'uses' => 'App\\Http\\Controllers\\API\\InfoController@index',
        'controller' => 'App\\Http\\Controllers\\API\\InfoController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodeDetails' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/pincodeDetails',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@pincodeDetails',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@pincodeDetails',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'pincodeDetails',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.readBy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/readBy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\MessageControler@readBy',
        'controller' => 'App\\Http\\Controllers\\API\\MessageControler@readBy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.readBy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'redDot' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/redDot',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\MessageControler@redDot',
        'controller' => 'App\\Http\\Controllers\\API\\MessageControler@redDot',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'redDot',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userToken' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/userToken',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@userToken',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@userToken',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'userToken',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverToken' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverToken',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverToken',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverToken',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverToken',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.automaticBookingCancel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/automaticBookingCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingCancel',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingCancel',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.automaticBookingCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.automaticBookingComplete' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/automaticBookingComplete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingComplete',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingComplete',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.automaticBookingComplete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'maintainance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/maintainance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@maintainance',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@maintainance',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'maintainance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.coupons' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/coupons',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@coupons',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@coupons',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.coupons',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'couponList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/couponList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@couponList',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@couponList',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'couponList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'appVerision' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appVerision',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@appVerision',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@appVerision',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'appVerision',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'reportDriver' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/reportDriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@reportDriver',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@reportDriver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'reportDriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentStatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/paymentStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@paymentStatus',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@paymentStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'paymentStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingStatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/bookingStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingStatus',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'bookingStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverDocument' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/driverDocument',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverDocument',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverDocument',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverDocument',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'activeRadius' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/activeRadius',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@activeRadius',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@activeRadius',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'activeRadius',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'checkIsRadius' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/checkIsRadius',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@checkIsRadius',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@checkIsRadius',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'checkIsRadius',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'getBookingViaLocation' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/getBookingViaLocation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getBookingViaLocation',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getBookingViaLocation',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'getBookingViaLocation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'googleSignIn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/googleSignIn',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@googleSignIn',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@googleSignIn',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'googleSignIn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'googleSignUp' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/googleSignUp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@googleSignUp',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@googleSignUp',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'googleSignUp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'drivercurrentlocation' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/drivercurrentlocation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@drivercurrentlocation',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@drivercurrentlocation',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'drivercurrentlocation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payment_status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payment_status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@payment_status',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@payment_status',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'payment_status',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverStatus' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverStatus',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'currentStatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/currentStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@currentStatus',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@currentStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'currentStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'availablePincode' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/availablePincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@availablePincode',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@availablePincode',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'availablePincode',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'deleteAddress' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/deleteAddress/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@deleteAddress',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@deleteAddress',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'deleteAddress',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'favouriteAddressList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/favouriteAddressList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@favouriteAddressList',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@favouriteAddressList',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'favouriteAddressList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'addAddress' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/addAddress',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@addAddress',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@addAddress',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'addAddress',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.userProfile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/userProfile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@userProfile',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@userProfile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.userProfile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'updateProfile' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/updateProfile/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@updateProfile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'updateProfile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'updateMobileNumber' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/updateMobileNumber/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@updateMobileNumber',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@updateMobileNumber',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'updateMobileNumber',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.forgotPassword' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/forgotPassword',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@forgot',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@forgot',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.forgotPassword',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.register' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@register',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@register',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.login' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@login',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@login',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sendMessage' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/sendMessage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\MessageControler@createMessage',
        'controller' => 'App\\Http\\Controllers\\API\\MessageControler@createMessage',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'sendMessage',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'reciveMessage' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/reciveMessage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\MessageControler@reciveMessage',
        'controller' => 'App\\Http\\Controllers\\API\\MessageControler@reciveMessage',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'reciveMessage',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profileUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/profiles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@profileUser',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@profileUser',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'profileUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userVerify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/profile/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@userVerify',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@userVerify',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'userVerify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingCalculation' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking/calculation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingCalculation',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingCalculation',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'bookingCalculation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'index' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking/{action}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\Booking@index',
        'controller' => 'App\\Http\\Controllers\\API\\Booking@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'companies.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'as' => 'companies.index',
        'uses' => 'App\\Http\\Controllers\\API\\CompanyController@index',
        'controller' => 'App\\Http\\Controllers\\API\\CompanyController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'companies.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'as' => 'companies.store',
        'uses' => 'App\\Http\\Controllers\\API\\CompanyController@store',
        'controller' => 'App\\Http\\Controllers\\API\\CompanyController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'companies.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'as' => 'companies.show',
        'uses' => 'App\\Http\\Controllers\\API\\CompanyController@show',
        'controller' => 'App\\Http\\Controllers\\API\\CompanyController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'companies.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'as' => 'companies.update',
        'uses' => 'App\\Http\\Controllers\\API\\CompanyController@update',
        'controller' => 'App\\Http\\Controllers\\API\\CompanyController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'companies.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'as' => 'companies.destroy',
        'uses' => 'App\\Http\\Controllers\\API\\CompanyController@destroy',
        'controller' => 'App\\Http\\Controllers\\API\\CompanyController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profileUpdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/profile/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@profileUpdate',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@profileUpdate',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'profileUpdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userFilter' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/user/filter/{action}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@userFilter',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@userFilter',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'userFilter',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'categoryList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/category/list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\Category@categoryList',
        'controller' => 'App\\Http\\Controllers\\API\\Category@categoryList',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'categoryList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverlogin' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverlogin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@driverlogin',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@driverlogin',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverlogin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverlogincheck' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverlogincheck',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@driverlogincheck',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@driverlogincheck',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverlogincheck',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincheck' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/pincheck',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@pincheck',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@pincheck',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'pincheck',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'notificationtodriver' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/notificationtodriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@notificationtodriver',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@notificationtodriver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'notificationtodriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userprofile' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/userprofile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\RegisterController@userprofile',
        'controller' => 'App\\Http\\Controllers\\API\\RegisterController@userprofile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'userprofile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverprofile' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverprofile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverprofile',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverprofile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'driverprofile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'drivercall' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/drivercall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@drivercall',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@drivercall',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'drivercall',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribercall' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/subscribercall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@subscribercall',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@subscribercall',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'subscribercall',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'notificationtodriverfirebase' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/notificationtodriverfirebase',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@notificationtodriverfirebase',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@notificationtodriverfirebase',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'notificationtodriverfirebase',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q2wUX33dhMmEBZ3f' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingtaxi',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingtaxi',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingtaxi',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Q2wUX33dhMmEBZ3f',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'rating' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/rating',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@rating',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@rating',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'rating',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WkeEEZa2P44cwvlu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingtaxi1',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingtaxi1',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingtaxi1',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::WkeEEZa2P44cwvlu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.bookingtaxi' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/bookingtaxi',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingtaxi',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingtaxi',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.bookingtaxi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.bookinghistory' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/bookinghistory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookinghistory',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookinghistory',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.bookinghistory',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.bookingdetailsoftheuser' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/bookingdetailsoftheuser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingdetailsoftheuser',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingdetailsoftheuser',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.bookingdetailsoftheuser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.getbookingdetailsofid' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/getbookingdetailsofid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getbookingdetailsofid',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getbookingdetailsofid',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.getbookingdetailsofid',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.livetrackinguser' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/livetrackinguser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@livetrackinguser',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@livetrackinguser',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.livetrackinguser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.locationuser' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/locationuser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@locationuser',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@locationuser',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.locationuser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.payment_status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/external/payment_status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@payment_status',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@payment_status',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.payment_status',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'external.paymentStatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/external/paymentStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'company.api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@paymentStatus',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@paymentStatus',
        'namespace' => NULL,
        'prefix' => 'api/external',
        'where' => 
        array (
        ),
        'as' => 'external.paymentStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TVu6OThpCX8RtJsC' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@logoutapi',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@logoutapi',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::TVu6OThpCX8RtJsC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Xm0nSiBE7WfjBCQ6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/getbookingdetailsofid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getbookingdetailsofid',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getbookingdetailsofid',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Xm0nSiBE7WfjBCQ6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CK98QeRpkOxh1Bft' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/getbookingdetails',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getbookingdetails',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getbookingdetails',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::CK98QeRpkOxh1Bft',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lJU21Sn1pdOckiDA' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/getlivelocation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getlivelocation',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getlivelocation',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::lJU21Sn1pdOckiDA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::btE2qWyvixoHMNRN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/banner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@banner',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@banner',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::btE2qWyvixoHMNRN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::H0olb8V6EM0OwVGD' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/voucher',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@voucher',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@voucher',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::H0olb8V6EM0OwVGD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::UWPlAnCrdws90twm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingaccept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingaccept',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingaccept',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::UWPlAnCrdws90twm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3LzntBEHcATW9hoS' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingprovideraccept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingprovideraccept',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingprovideraccept',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3LzntBEHcATW9hoS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EILOptPYGpJKeRnB' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking_provider_pending_list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@booking_provider_pending_list',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@booking_provider_pending_list',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::EILOptPYGpJKeRnB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ibwFIpi1gZcLqauw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking_provider_assign_driver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@booking_provider_assign_driver',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@booking_provider_assign_driver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ibwFIpi1gZcLqauw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TvV6OsdgjcPm6VxN' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingignore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingignore',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingignore',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::TvV6OsdgjcPm6VxN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CKjFmEGR6yAKbpp0' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingcancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingcancel',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingcancel',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::CKjFmEGR6yAKbpp0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iMEIAdlnZwklaRU6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/userbookingcancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@userbookingcancel',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@userbookingcancel',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::iMEIAdlnZwklaRU6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6FOtcZIttiGoSGKr' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/userbookingcancelwithoutreason',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@userbookingcancelwithoutreason',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@userbookingcancelwithoutreason',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6FOtcZIttiGoSGKr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JhovcClyDlAMmILw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/livetrackingdriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@livetrackingdriver',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@livetrackingdriver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::JhovcClyDlAMmILw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8HA89sYN9j8klJUw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/livetrackinguser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@livetrackinguser',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@livetrackinguser',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8HA89sYN9j8klJUw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EEj5qGJbogoW8PVf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookingdetailsoftheuser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'detect.company',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookingdetailsoftheuser',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookingdetailsoftheuser',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::EEj5qGJbogoW8PVf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Xi1VCiw83LZwRrAa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverbookingcomplete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverbookingcomplete',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverbookingcomplete',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Xi1VCiw83LZwRrAa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uhPcOl7OaB0pKsak' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/locationuser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@locationuser',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@locationuser',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::uhPcOl7OaB0pKsak',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AOxXsee3e1tJJLtk' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/locationdriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@locationdriver',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@locationdriver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::AOxXsee3e1tJJLtk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xklBVFCH9nv4J3xj' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/checkavailabledriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@checkavailabledriver',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@checkavailabledriver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::xklBVFCH9nv4J3xj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jLcqlOCOnUMoJwVf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/otpverification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@otpverification',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@otpverification',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::jLcqlOCOnUMoJwVf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xhBc8uQUN2nrcUnt' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/otpcompleteverification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@otpcompleteverification',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@otpcompleteverification',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::xhBc8uQUN2nrcUnt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4SXGNJqueTyqa4Wd' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/getstatusofbooking',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@getstatusofbooking',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@getstatusofbooking',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4SXGNJqueTyqa4Wd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ez7nNKJx7q3Noe2p' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookinghistory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@bookinghistory',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@bookinghistory',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Ez7nNKJx7q3Noe2p',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KmzaB1FzA2Dsk8DL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/driverhistory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@driverhistory',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@driverhistory',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::KmzaB1FzA2Dsk8DL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userBookingHistory' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/userBookingHistory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@userBookingHistory',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@userBookingHistory',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'userBookingHistory',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dOHXShUUGB6xV9Mf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/sendnotificationtootherdriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@sendnotificationtootherdriver',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@sendnotificationtootherdriver',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::dOHXShUUGB6xV9Mf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RZK1GdwbM5CgyNkB' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/drivertocustomercall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@drivertocustomercall',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@drivertocustomercall',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RZK1GdwbM5CgyNkB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'checkNumber' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/checkNumber',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@checkNumber',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@checkNumber',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'checkNumber',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9xOUiDj3szMezcQF' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/generate_referral_code',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@generate_referral_code',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@generate_referral_code',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9xOUiDj3szMezcQF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rXrMfy8f6dmwN2MB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accountDeletion',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => '\\Illuminate\\Routing\\ViewController@__invoke',
        'controller' => '\\Illuminate\\Routing\\ViewController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::rXrMfy8f6dmwN2MB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
        'view' => 'accountDelation',
        'data' => 
        array (
        ),
        'status' => 200,
        'headers' => 
        array (
        ),
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LRqtgW23a18Y0eJZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'generate_referral_code',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@generate_referral_code',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@generate_referral_code',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::LRqtgW23a18Y0eJZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'zipcodeE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'zipcodeE',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@zipcodeE',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@zipcodeE',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'zipcodeE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'web.automaticBookingComplete' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'automaticBookingComplete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingComplete',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingComplete',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'web.automaticBookingComplete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'web.automaticBookingCancel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'automaticBookingCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingCancel',
        'controller' => 'App\\Http\\Controllers\\API\\otherController@automaticBookingCancel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'web.automaticBookingCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::foQikCp6kRI786A2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tc',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@tc',
        'controller' => 'App\\Http\\Controllers\\websiteController@tc',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::foQikCp6kRI786A2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y8WwitkY9jow1HeY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'returnandrefundpolicy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@returnandrefundpolicy',
        'controller' => 'App\\Http\\Controllers\\websiteController@returnandrefundpolicy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::y8WwitkY9jow1HeY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Kb06RPP5c70io4QQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'privacypolicyandcookies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@privacypolicyandcookies',
        'controller' => 'App\\Http\\Controllers\\websiteController@privacypolicyandcookies',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::Kb06RPP5c70io4QQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dcKNuxijR5dBfL8J' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\CacheController@clear',
        'controller' => 'App\\Http\\Controllers\\CacheController@clear',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::dcKNuxijR5dBfL8J',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@showLoginForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@showLoginForm',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VBmVlvT2NtouqGJ6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@login',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@login',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::VBmVlvT2NtouqGJ6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@logout',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@logout',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@showRegistrationForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@showRegistrationForm',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::99cvMJwbHqjacRf0' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@register',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@register',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::99cvMJwbHqjacRf0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'password/reset',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@showLinkRequestForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@showLinkRequestForm',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'password/email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@sendResetLinkEmail',
        'controller' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@sendResetLinkEmail',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.email',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'password/reset/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ResetPasswordController@showResetForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ResetPasswordController@showResetForm',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.reset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'password/reset',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ResetPasswordController@reset',
        'controller' => 'App\\Http\\Controllers\\Auth\\ResetPasswordController@reset',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'password/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmPasswordController@showConfirmForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmPasswordController@showConfirmForm',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HkIgRUDi0NH2TtVh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'password/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmPasswordController@confirm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmPasswordController@confirm',
        'namespace' => 'App\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::HkIgRUDi0NH2TtVh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'usedPincodes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'usedPincodes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@usedPincodes',
        'controller' => 'App\\Http\\Controllers\\HomeController@usedPincodes',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'usedPincodes',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincodebasedcategory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincodebasedcategory/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pincodebasedcategory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincodebasedcategory/{pincodebasedcategory}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincodebasedcategory/{pincodebasedcategory}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'pincodebasedcategory/{pincodebasedcategory}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodebasedcategory.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pincodebasedcategory/{pincodebasedcategory}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodebasedcategory.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.pincodebasedcategorystatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincodebasedcategorystatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@pincodebasedcategorystatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@pincodebasedcategorystatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.pincodebasedcategorystatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'makePincodeBasedCategory' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'makePincodeBasedCategory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@makePincodeBasedCategory',
        'controller' => 'App\\Http\\Controllers\\Admin\\PincodebasedcategoryController@makePincodeBasedCategory',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'makePincodeBasedCategory',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'home',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@dashboard',
        'controller' => 'App\\Http\\Controllers\\HomeController@dashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'createpincode' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'createpincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@create',
        'controller' => 'App\\Http\\Controllers\\PincodeController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'createpincode',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincode' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@index',
        'controller' => 'App\\Http\\Controllers\\PincodeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pincode',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xuxZCZX99zDxIFNm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pincodestore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@pincodestore',
        'controller' => 'App\\Http\\Controllers\\PincodeController@pincodestore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::xuxZCZX99zDxIFNm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincode.search' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincode/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@searchPincode',
        'controller' => 'App\\Http\\Controllers\\PincodeController@searchPincode',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pincode.search',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'search.pincode' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'search-pincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@searchPincode',
        'controller' => 'App\\Http\\Controllers\\PincodeController@searchPincode',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'search.pincode',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8BUXXmqSLjSGjAwc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincode/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@edit',
        'controller' => 'App\\Http\\Controllers\\PincodeController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::8BUXXmqSLjSGjAwc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lI9qc6TliUdmnBjO' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'pincodeupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@update',
        'controller' => 'App\\Http\\Controllers\\PincodeController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::lI9qc6TliUdmnBjO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::67kysRy8CxWlNDAq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pincodedelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PincodeController@destroy',
        'controller' => 'App\\Http\\Controllers\\PincodeController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::67kysRy8CxWlNDAq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'createSubscriber' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'createSubscriber',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@create',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'createSubscriber',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@subscriber',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@subscriber',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.withoutEmployeeId' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberList/self-registered-subscribers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@subscribersWithoutEmployeeId',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@subscribersWithoutEmployeeId',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.withoutEmployeeId',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::W9Lmn1ite3LC4IWM' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriberstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@subscriberstore',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@subscriberstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::W9Lmn1ite3LC4IWM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.onboarding.otp.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriber/onboarding/send-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:3,1',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@sendOnboardingOtp',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@sendOnboardingOtp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.onboarding.otp.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.onboarding.otp.verify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriber/onboarding/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:10,1',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@verifyOnboardingOtp',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@verifyOnboardingOtp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.onboarding.otp.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.otp.form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriber/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@showOtpVerification',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@showOtpVerification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.otp.form',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.otp.verify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriber/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:10,1',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@verifyOtp',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@verifyOtp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.otp.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.otp.resend' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriber/resend-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:3,1',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@resendOtp',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@resendOtp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.otp.resend',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriber.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriber/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@show',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriber.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'id' => '[0-9]+',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tDHLsAGxwxIcL9sE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriber/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@edit',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::tDHLsAGxwxIcL9sE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'id' => '[0-9]+',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::etZ0IWdkpNkHgsy8' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'subscriberupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@update',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::etZ0IWdkpNkHgsy8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'id' => '[0-9]+',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mMfN4xErld2lJGxG' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberdelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@destroy',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::mMfN4xErld2lJGxG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'id' => '[0-9]+',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GPOcPDArP7VtjEmk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'changeStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@changeStatus',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@changeStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::GPOcPDArP7VtjEmk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'expiredsubscriber' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expiredsubscriber',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@expiry',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@expiry',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'expiredsubscriber',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::UlrLzxGWJVSEZsFu' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'subscriberblock/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@block',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@block',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::UlrLzxGWJVSEZsFu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::i4rfeXIIRUv89wXy' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriberunblock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@subscriberunblock',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@subscriberunblock',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::i4rfeXIIRUv89wXy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7usEiP2uQpnIfth7' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'deletesubscribervideo/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@deletesubscribervideo',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@deletesubscribervideo',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::7usEiP2uQpnIfth7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enduser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'enduser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@enduser',
        'controller' => 'App\\Http\\Controllers\\HomeController@enduser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'enduser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blockenduser' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'blockenduser/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@blockEndUser',
        'controller' => 'App\\Http\\Controllers\\HomeController@blockEndUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'blockenduser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'unblockenduser' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'unblockenduser/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@unblockEndUser',
        'controller' => 'App\\Http\\Controllers\\HomeController@unblockEndUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'unblockenduser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'category' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'category',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@category',
        'controller' => 'App\\Http\\Controllers\\HomeController@category',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'category',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PhPgnHEdeVul93cM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'categoryStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@categoryStatus',
        'controller' => 'App\\Http\\Controllers\\HomeController@categoryStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::PhPgnHEdeVul93cM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'drivers' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driver',
        'controller' => 'App\\Http\\Controllers\\HomeController@driver',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'drivers',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driver.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driver/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@createdriver',
        'controller' => 'App\\Http\\Controllers\\HomeController@createdriver',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'driver.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YHopm2wl9q8prSSc' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'driverstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driverstore',
        'controller' => 'App\\Http\\Controllers\\HomeController@driverstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::YHopm2wl9q8prSSc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.driver.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driver/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@show',
        'controller' => 'App\\Http\\Controllers\\HomeController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.driver.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::I1x6x0tNfmJKby7O' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driverActivate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driverActivate',
        'controller' => 'App\\Http\\Controllers\\HomeController@driverActivate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::I1x6x0tNfmJKby7O',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BbzymYIIvGI5XHrm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driver/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driveredit',
        'controller' => 'App\\Http\\Controllers\\HomeController@driveredit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::BbzymYIIvGI5XHrm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tcSQ9VhX7Q3Ed79m' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'driverupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driverupdate',
        'controller' => 'App\\Http\\Controllers\\HomeController@driverupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::tcSQ9VhX7Q3Ed79m',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uXsr7TeAKCnvub9W' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driverNotification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@drivernotify',
        'controller' => 'App\\Http\\Controllers\\HomeController@drivernotify',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::uXsr7TeAKCnvub9W',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dot-notify' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dot-notify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@dotnotify',
        'controller' => 'App\\Http\\Controllers\\HomeController@dotnotify',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dot-notify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.readBy' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'readBy/{readBy}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@readBy',
        'controller' => 'App\\Http\\Controllers\\HomeController@readBy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.readBy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nUP20CK9cV3AXq7x' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'driverblock/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@driverblock',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@driverblock',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::nUP20CK9cV3AXq7x',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tBf5UmFbMZLpf91I' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'driverunblock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberController@driverunblock',
        'controller' => 'App\\Http\\Controllers\\SubscriberController@driverunblock',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::tBf5UmFbMZLpf91I',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberblockList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberblockList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@subscriberblockList',
        'controller' => 'App\\Http\\Controllers\\HomeController@subscriberblockList',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberblockList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'adminBlockeddriver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'adminBlocked',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@adminBlockeddriver',
        'controller' => 'App\\Http\\Controllers\\HomeController@adminBlockeddriver',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'adminBlockeddriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverblockList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driverblockList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driverblockList',
        'controller' => 'App\\Http\\Controllers\\HomeController@driverblockList',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'driverblockList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberunblockList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberunblockList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@subscriberunblockList',
        'controller' => 'App\\Http\\Controllers\\HomeController@subscriberunblockList',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberunblockList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'adminUnblockeddriver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'adminUnblocked',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@adminUnblockeddriver',
        'controller' => 'App\\Http\\Controllers\\HomeController@adminUnblockeddriver',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'adminUnblockeddriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverunblockList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'driverunblockList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@driverunblockList',
        'controller' => 'App\\Http\\Controllers\\HomeController@driverunblockList',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'driverunblockList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.coupons' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupons',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@coupons',
        'controller' => 'App\\Http\\Controllers\\HomeController@coupons',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.coupons',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.createcoupons' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupons/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@createcoupons',
        'controller' => 'App\\Http\\Controllers\\HomeController@createcoupons',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.createcoupons',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::s5OIPIeE9LbWTTur' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'couponsstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@couponsstore',
        'controller' => 'App\\Http\\Controllers\\HomeController@couponsstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::s5OIPIeE9LbWTTur',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HEZVll0eupHyY16x' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupons/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@couponsedit',
        'controller' => 'App\\Http\\Controllers\\HomeController@couponsedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::HEZVll0eupHyY16x',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LMiQ2rtfIRXukGui' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'couponsupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@couponsupdate',
        'controller' => 'App\\Http\\Controllers\\HomeController@couponsupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::LMiQ2rtfIRXukGui',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4ew0T13NIr0VV8Kx' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'couponsdelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@couponsdestroy',
        'controller' => 'App\\Http\\Controllers\\HomeController@couponsdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::4ew0T13NIr0VV8Kx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::isLL7UB6wnWsdKgX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'couponsActivate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@couponsActivate',
        'controller' => 'App\\Http\\Controllers\\HomeController@couponsActivate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::isLL7UB6wnWsdKgX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'priceNotification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'priceNotification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@subscriberpriceNotify',
        'controller' => 'App\\Http\\Controllers\\HomeController@subscriberpriceNotify',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'priceNotification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberNotification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberNotification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@subscriberNotification',
        'controller' => 'App\\Http\\Controllers\\HomeController@subscriberNotification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberNotification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U6GFNj1MDXZgYj8O' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'markread',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@markread',
        'controller' => 'App\\Http\\Controllers\\HomeController@markread',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::U6GFNj1MDXZgYj8O',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3lJtm8mpeCkZmCVI' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'markasread',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@markasread',
        'controller' => 'App\\Http\\Controllers\\HomeController@markasread',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::3lJtm8mpeCkZmCVI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'enquiry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.index',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@index',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'enquiry/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.create',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@create',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'enquiry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.store',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@store',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'enquiry/{enquiry}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.show',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@show',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'enquiry/{enquiry}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.edit',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@edit',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'enquiry/{enquiry}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.update',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@update',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiry.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'enquiry/{enquiry}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiry.destroy',
        'uses' => 'App\\Http\\Controllers\\EnquiryController@destroy',
        'controller' => 'App\\Http\\Controllers\\EnquiryController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'excelpincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.index',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@index',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'excelpincode/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.create',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@create',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'excelpincode',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.store',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@store',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'excelpincode/{excelPincode}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.show',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@show',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'excelpincode/{excelPincode}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.edit',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@edit',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'excelpincode/{excelPincode}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.update',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@update',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'excelpincode.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'excelpincode/{excelPincode}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'excelpincode.destroy',
        'uses' => 'App\\Http\\Controllers\\ExcelPincodeController@destroy',
        'controller' => 'App\\Http\\Controllers\\ExcelPincodeController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaints',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.index',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@index',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaints/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.create',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@create',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'complaints',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.store',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@store',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaints/{complaint}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.show',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@show',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaints/{complaint}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.edit',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@edit',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'complaints/{complaint}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.update',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@update',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaints.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'complaints/{complaint}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaints.destroy',
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@destroy',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaint' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaint/{complaint}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ComplaintsController@actionedBy',
        'controller' => 'App\\Http\\Controllers\\ComplaintsController@actionedBy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'complaint',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.index',
        'uses' => 'App\\Http\\Controllers\\RoleController@index',
        'controller' => 'App\\Http\\Controllers\\RoleController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'role/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.create',
        'uses' => 'App\\Http\\Controllers\\RoleController@create',
        'controller' => 'App\\Http\\Controllers\\RoleController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.store',
        'uses' => 'App\\Http\\Controllers\\RoleController@store',
        'controller' => 'App\\Http\\Controllers\\RoleController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'role/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.show',
        'uses' => 'App\\Http\\Controllers\\RoleController@show',
        'controller' => 'App\\Http\\Controllers\\RoleController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'role/{role}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.edit',
        'uses' => 'App\\Http\\Controllers\\RoleController@edit',
        'controller' => 'App\\Http\\Controllers\\RoleController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'role/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.update',
        'uses' => 'App\\Http\\Controllers\\RoleController@update',
        'controller' => 'App\\Http\\Controllers\\RoleController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'role/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'role.destroy',
        'uses' => 'App\\Http\\Controllers\\RoleController@destroy',
        'controller' => 'App\\Http\\Controllers\\RoleController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.index',
        'uses' => 'App\\Http\\Controllers\\UserController@index',
        'controller' => 'App\\Http\\Controllers\\UserController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.create',
        'uses' => 'App\\Http\\Controllers\\UserController@create',
        'controller' => 'App\\Http\\Controllers\\UserController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.store',
        'uses' => 'App\\Http\\Controllers\\UserController@store',
        'controller' => 'App\\Http\\Controllers\\UserController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.show',
        'uses' => 'App\\Http\\Controllers\\UserController@show',
        'controller' => 'App\\Http\\Controllers\\UserController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.edit',
        'uses' => 'App\\Http\\Controllers\\UserController@edit',
        'controller' => 'App\\Http\\Controllers\\UserController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.update',
        'uses' => 'App\\Http\\Controllers\\UserController@update',
        'controller' => 'App\\Http\\Controllers\\UserController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'users.destroy',
        'uses' => 'App\\Http\\Controllers\\UserController@destroy',
        'controller' => 'App\\Http\\Controllers\\UserController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile/{admin}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'profile.edit',
        'uses' => 'App\\Http\\Controllers\\ProfileController@edit',
        'controller' => 'App\\Http\\Controllers\\ProfileController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'profile/{admin}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'profile.update',
        'uses' => 'App\\Http\\Controllers\\ProfileController@update',
        'controller' => 'App\\Http\\Controllers\\ProfileController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintReport.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaintReport/{admin}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'complaintReport.show',
        'uses' => 'App\\Http\\Controllers\\ComplaintReportController@show',
        'controller' => 'App\\Http\\Controllers\\ComplaintReportController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaintExcel/{admin}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ComplaintReportController@downloadExcel',
        'controller' => 'App\\Http\\Controllers\\ComplaintReportController@downloadExcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'complaintExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintPdf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'complaintPdf/{admin}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ComplaintReportController@downloadPdf',
        'controller' => 'App\\Http\\Controllers\\ComplaintReportController@downloadPdf',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'complaintPdf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'messageEmployee' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'messageEmployee',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberModelController@messageEmployee',
        'controller' => 'App\\Http\\Controllers\\SubscriberModelController@messageEmployee',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'messageEmployee',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberLogin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberModelController@index',
        'controller' => 'App\\Http\\Controllers\\SubscriberModelController@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscriberLogin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dwv1Qsk8I9QT1j2g' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/loginaction',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberModelController@subscriberlogin',
        'controller' => 'App\\Http\\Controllers\\SubscriberModelController@subscriberlogin',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::dwv1Qsk8I9QT1j2g',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.index',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@index',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/roles/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.create',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@create',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@create',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.store',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@store',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@store',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/roles/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.show',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@show',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/roles/{role}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.edit',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@edit',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@edit',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'subscribers/roles/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.update',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@update',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@update',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'subscribers/roles/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'roles.destroy',
        'uses' => 'App\\Http\\Controllers\\SubRoleController@destroy',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@destroy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wlMRBfA5NvYB8FFX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/subPermission',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubRoleController@subPermission',
        'controller' => 'App\\Http\\Controllers\\SubRoleController@subPermission',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::wlMRBfA5NvYB8FFX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/subEmployee',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.index',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@index',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/subEmployee/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.create',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@create',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@create',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/subEmployee',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.store',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@store',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@store',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/subEmployee/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.show',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@show',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/subEmployee/{employee}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.edit',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@edit',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@edit',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'subscribers/subEmployee/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.update',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@update',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@update',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subEmployee.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'subscribers/subEmployee/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subEmployee.destroy',
        'uses' => 'App\\Http\\Controllers\\SubEmployeeController@destroy',
        'controller' => 'App\\Http\\Controllers\\SubEmployeeController@destroy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodes.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/pincodes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodes.index',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\PincodebasedcategoryController@index',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\PincodebasedcategoryController@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pincodes.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/pincodes/{pincode}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pincodes.show',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\PincodebasedcategoryController@show',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\PincodebasedcategoryController@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.pincodebasedcategorystatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/pincodebasedcategorystatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Subscriber\\PincodebasedcategoryController@pincodebasedcategorystatus',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\PincodebasedcategoryController@pincodebasedcategorystatus',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.pincodebasedcategorystatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberForgotPassword' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberForgotPassword',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@subscriberForgotPassword',
        'controller' => 'App\\Http\\Controllers\\PasswordController@subscriberForgotPassword',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberForgotPassword',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberEmailVerification' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriberEmailVerification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@subscriberEmailVerification',
        'controller' => 'App\\Http\\Controllers\\PasswordController@subscriberEmailVerification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberEmailVerification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberpasswordReset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberpasswordReset/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@subscriberPasswordReset',
        'controller' => 'App\\Http\\Controllers\\PasswordController@subscriberPasswordReset',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberpasswordReset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberConfimed_password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriberConfimed_password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@subscriberNewPassword',
        'controller' => 'App\\Http\\Controllers\\PasswordController@subscriberNewPassword',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberConfimed_password',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.logout' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@logout',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@logout',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@dashboard',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@dashboard',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'createdriver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/createDriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@createdriver',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@createdriver',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'createdriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@driver',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@driver',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'driver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::52VZHmihM3XfyLJN' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/driverstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@driverstore',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@driverstore',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::52VZHmihM3XfyLJN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.driver.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driver/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@show',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.driver.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::o8WXDJphsFCwvoBP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driver/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@edit',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@edit',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::o8WXDJphsFCwvoBP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::i3m2wAekxxMwSKCN' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'subscribers/driverupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@update',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@update',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::i3m2wAekxxMwSKCN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Sw0dJU6At3mlQxyu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driverdelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@destroy',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@destroy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::Sw0dJU6At3mlQxyu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DbnYS6rL8QjeMwDp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driverStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@driverStatus',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@driverStatus',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::DbnYS6rL8QjeMwDp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blockedDriver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/blockedDriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@blocked',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@blocked',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'blockedDriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::APPdo6OBWEUNn9iR' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'subscribers/driverblock/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@driverblock',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@driverblock',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::APPdo6OBWEUNn9iR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NuOIG1rQ2N7cn29g' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/driverunblock',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@driverunblock',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@driverunblock',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::NuOIG1rQ2N7cn29g',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@profile',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@profile',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.profile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bBihmcYI5YOa9uhR' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/updateProfile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@updateProfile',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::bBihmcYI5YOa9uhR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.price' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/price',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@price',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@price',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.price',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zqkkCspYvngrclgK' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/pricestore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@pricestore',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@pricestore',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::zqkkCspYvngrclgK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupons' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/coupons',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@coupons',
        'controller' => 'App\\Http\\Controllers\\CouponController@coupons',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.coupons',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.createcoupons' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/coupons/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@createcoupons',
        'controller' => 'App\\Http\\Controllers\\CouponController@createcoupons',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.createcoupons',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WngXOIwfKhwzMs2P' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/couponsstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@couponsstore',
        'controller' => 'App\\Http\\Controllers\\CouponController@couponsstore',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::WngXOIwfKhwzMs2P',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yqGhRJqNQTukL9GQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/coupons/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@couponsedit',
        'controller' => 'App\\Http\\Controllers\\CouponController@couponsedit',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::yqGhRJqNQTukL9GQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6tjmLTgsMe2moKD9' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'subscribers/couponsupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@couponsupdate',
        'controller' => 'App\\Http\\Controllers\\CouponController@couponsupdate',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::6tjmLTgsMe2moKD9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ixMLbJ91KGXq8SAt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/couponsdelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@couponsdestroy',
        'controller' => 'App\\Http\\Controllers\\CouponController@couponsdestroy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::ixMLbJ91KGXq8SAt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hlGCY8omj3gOdlWT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/couponsActivate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@couponsActivate',
        'controller' => 'App\\Http\\Controllers\\CouponController@couponsActivate',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::hlGCY8omj3gOdlWT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.complaints' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/complaints',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@complaints',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@complaints',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.complaints',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.complaintsform' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/complaints/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@complaintsform',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@complaintsform',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.complaintsform',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.complaintsstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/complaintsstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@complaintsstore',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@complaintsstore',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.complaintsstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintShow' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/complaints/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@complaintsshow',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@complaintsshow',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'complaintShow',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintEdit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/complaintEdit/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@edit',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@edit',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'complaintEdit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintUpdate' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'subscribers/complaintUpdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@update',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@update',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'complaintUpdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'complaintAction' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/complaintAction/{complaint}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@actionedBy',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@actionedBy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'complaintAction',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.enquiry' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/enquiry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@enquiry',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@enquiry',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.enquiry',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.enquiryform' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/enquiry/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@enquiryform',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@enquiryform',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.enquiryform',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.enquirystore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/enquirysstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@enquirystore',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@enquirystore',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'subscribers.enquirystore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::M1hCoEgXjVfVAHyg' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/enquiry/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberActionController@enquiryshow',
        'controller' => 'App\\Http\\Controllers\\SubscriberActionController@enquiryshow',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'generated::M1hCoEgXjVfVAHyg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/bookingReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.index',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@index',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/bookingReport/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.create',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@create',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@create',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/bookingReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.store',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@store',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@store',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/bookingReport/{bookingReport}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.show',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@show',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/bookingReport/{bookingReport}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.edit',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@edit',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@edit',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'subscribers/bookingReport/{bookingReport}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.update',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@update',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@update',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'subscribers/bookingReport/{bookingReport}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'bookingReport.destroy',
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@destroy',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@destroy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadsExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/downloadsExcel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@downloadexcel',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@downloadexcel',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'downloadsExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadsPDF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/downloadsPDF',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberBookingReportController@downloadPDF',
        'controller' => 'App\\Http\\Controllers\\SubscriberBookingReportController@downloadPDF',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'downloadsPDF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverReport.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driverReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'driverReport.index',
        'uses' => 'App\\Http\\Controllers\\DriverBookingReport@index',
        'controller' => 'App\\Http\\Controllers\\DriverBookingReport@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverReport.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driverReport/{subscriber}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'driverReport.show',
        'uses' => 'App\\Http\\Controllers\\DriverBookingReport@show',
        'controller' => 'App\\Http\\Controllers\\DriverBookingReport@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverExcelDownload' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driverExcelDownload/{driver}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\DriverBookingReport@downloadExcel',
        'controller' => 'App\\Http\\Controllers\\DriverBookingReport@downloadExcel',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'driverExcelDownload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'driverPdfDownload' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/driverPdfDownload/{driver}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'uses' => 'App\\Http\\Controllers\\DriverBookingReport@downloadPdf',
        'controller' => 'App\\Http\\Controllers\\DriverBookingReport@downloadPdf',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
        'as' => 'driverPdfDownload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupon.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/coupon',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'subscribers.coupon.index',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\CouponController@index',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\CouponController@index',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupon.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/coupon/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'subscribers.coupon.create',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\CouponController@create',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\CouponController@create',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupon.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscribers/coupon',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'subscribers.coupon.store',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\CouponController@store',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\CouponController@store',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupon.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscribers/coupon/{coupon}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'subscribers.coupon.show',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\CouponController@show',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\CouponController@show',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupon.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'subscribers/coupon/{coupon}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'subscribers.coupon.update',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\CouponController@update',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\CouponController@update',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscribers.coupon.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'subscribers/coupon/{coupon}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'subscribers',
        ),
        'as' => 'subscribers.coupon.destroy',
        'uses' => 'App\\Http\\Controllers\\Subscriber\\CouponController@destroy',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\CouponController@destroy',
        'namespace' => NULL,
        'prefix' => '/subscribers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oNOFIOARHFIYenpe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@home',
        'controller' => 'App\\Http\\Controllers\\websiteController@home',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::oNOFIOARHFIYenpe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yGJ3xTNueymltYpf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'about',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@about',
        'controller' => 'App\\Http\\Controllers\\websiteController@about',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::yGJ3xTNueymltYpf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HlyfraW0R7QN6eVE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@services',
        'controller' => 'App\\Http\\Controllers\\websiteController@services',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::HlyfraW0R7QN6eVE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5MXh4FLPwoaeQhVj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'contact',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@contact',
        'controller' => 'App\\Http\\Controllers\\websiteController@contact',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::5MXh4FLPwoaeQhVj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EtoQKgBEVtop9bV5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pbp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@pbp',
        'controller' => 'App\\Http\\Controllers\\websiteController@pbp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::EtoQKgBEVtop9bV5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lX1UItMeFdbR4HL0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'readmore/{what}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@readmore',
        'controller' => 'App\\Http\\Controllers\\websiteController@readmore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::lX1UItMeFdbR4HL0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'inquirystore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'inquirystore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\InquiryController@store',
        'controller' => 'App\\Http\\Controllers\\InquiryController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'inquirystore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'feedback' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'feedback',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\FeedbackController@store',
        'controller' => 'App\\Http\\Controllers\\FeedbackController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'feedback',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storeNewsletter' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'storeNewsletter',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\NewsLetterController@store',
        'controller' => 'App\\Http\\Controllers\\NewsLetterController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'storeNewsletter',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zxUigAtBW3m0UpPf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@admin',
        'controller' => 'App\\Http\\Controllers\\websiteController@admin',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::zxUigAtBW3m0UpPf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4trHfPg9SAWINaUm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@admin',
        'controller' => 'App\\Http\\Controllers\\websiteController@admin',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::4trHfPg9SAWINaUm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'siteupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/siteupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@siteupdate',
        'controller' => 'App\\Http\\Controllers\\websiteController@siteupdate',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'as' => 'siteupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZM1SsiHIpwyJur1x' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/slider',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@slider',
        'controller' => 'App\\Http\\Controllers\\websiteController@slider',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::ZM1SsiHIpwyJur1x',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'siteupdateimage' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/siteupdateimage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@siteupdateimage',
        'controller' => 'App\\Http\\Controllers\\websiteController@siteupdateimage',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'as' => 'siteupdateimage',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::o0VdhHsPwdw4QhsO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/delete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@sliderdelete',
        'controller' => 'App\\Http\\Controllers\\websiteController@sliderdelete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'as' => 'generated::o0VdhHsPwdw4QhsO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pushnotification.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pushnotification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pushnotification.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\PushnotificationController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PushnotificationController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pushnotification.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/pushnotification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'pushnotification.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\PushnotificationController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\PushnotificationController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'forgotPassword' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'forgotPassword',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@forgotPassword',
        'controller' => 'App\\Http\\Controllers\\PasswordController@forgotPassword',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'forgotPassword',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'emailVerification' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'emailVerification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@sendForgotPasswordEmail',
        'controller' => 'App\\Http\\Controllers\\PasswordController@sendForgotPasswordEmail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'emailVerification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passwordReset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'passwordReset/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@passwordReset',
        'controller' => 'App\\Http\\Controllers\\PasswordController@passwordReset',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'passwordReset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'confimed_password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'confimed_password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PasswordController@newPassword',
        'controller' => 'App\\Http\\Controllers\\PasswordController@newPassword',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'confimed_password',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'newsletter' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'newsletter',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\NewsLetterController@index',
        'controller' => 'App\\Http\\Controllers\\NewsLetterController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'newsletter',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'newsLetterExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'newsLetterExcel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\NewsLetterController@newsLetterExcel',
        'controller' => 'App\\Http\\Controllers\\NewsLetterController@newsLetterExcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'newsLetterExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'newsLetterPDF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'newsLetterPDF',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\NewsLetterController@newsLetterPDF',
        'controller' => 'App\\Http\\Controllers\\NewsLetterController@newsLetterPDF',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'newsLetterPDF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'statusactive' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'statusactive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@statusactive',
        'controller' => 'App\\Http\\Controllers\\websiteController@statusactive',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'statusactive',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lJPBSNZl5cpGKGtp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'statusonoroff',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\websiteController@statusonoroff',
        'controller' => 'App\\Http\\Controllers\\websiteController@statusonoroff',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::lJPBSNZl5cpGKGtp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberstatuschange' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'subscriberstatuschange',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@subscriberstatuschange',
        'controller' => 'App\\Http\\Controllers\\otherController@subscriberstatuschange',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberstatuschange',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'makepayment' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'makepayment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@makesubscribtionpayment',
        'controller' => 'App\\Http\\Controllers\\otherController@makesubscribtionpayment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'makepayment',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'validId' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'validId',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@validId',
        'controller' => 'App\\Http\\Controllers\\otherController@validId',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'validId',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'successfullypayment' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'successfullypayment/{user_id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@successfullypayment',
        'controller' => 'App\\Http\\Controllers\\otherController@successfullypayment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'successfullypayment',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'successpay' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'successpay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@successpay',
        'controller' => 'App\\Http\\Controllers\\otherController@successpay',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'successpay',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chatwithadmin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chatwithadmin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@chatwithadmin',
        'controller' => 'App\\Http\\Controllers\\otherController@chatwithadmin',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chatwithadmin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chat/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@chat',
        'controller' => 'App\\Http\\Controllers\\otherController@chat',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberchatwithadmin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberchatwithadmin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@subscriberchatwithadmin',
        'controller' => 'App\\Http\\Controllers\\otherController@subscriberchatwithadmin',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberchatwithadmin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1MfuiIpqXa6Xnu4S' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'rl',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@rl',
        'controller' => 'App\\Http\\Controllers\\otherController@rl',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::1MfuiIpqXa6Xnu4S',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CQgmIP8qScrtufZc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chatsupport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@chatsupport',
        'controller' => 'App\\Http\\Controllers\\otherController@chatsupport',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::CQgmIP8qScrtufZc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YZoXldre0uhblPbt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'Banner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@Banner',
        'controller' => 'App\\Http\\Controllers\\otherController@Banner',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::YZoXldre0uhblPbt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bannersubmit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'bannersubmit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@bannersubmit',
        'controller' => 'App\\Http\\Controllers\\otherController@bannersubmit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'bannersubmit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bannerdelete' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'bannerdelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@bannerdelete',
        'controller' => 'App\\Http\\Controllers\\otherController@bannerdelete',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'bannerdelete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bannereditsubmit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'bannereditsubmit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@bannereditsubmit',
        'controller' => 'App\\Http\\Controllers\\otherController@bannereditsubmit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'bannereditsubmit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QEZ9zvfZ5pA2BVN0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'Voucher',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@Voucher',
        'controller' => 'App\\Http\\Controllers\\otherController@Voucher',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::QEZ9zvfZ5pA2BVN0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vouchersubmit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'vouchersubmit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@vouchersubmit',
        'controller' => 'App\\Http\\Controllers\\otherController@vouchersubmit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'vouchersubmit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vouchereditsubmit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'vouchereditsubmit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@vouchereditsubmit',
        'controller' => 'App\\Http\\Controllers\\otherController@vouchereditsubmit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'vouchereditsubmit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucherdelete' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'voucherdelete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\otherController@voucherdelete',
        'controller' => 'App\\Http\\Controllers\\otherController@voucherdelete',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'voucherdelete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookingReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'bookingReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\BookingReportController@index',
        'controller' => 'App\\Http\\Controllers\\BookingReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'bookingReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'viewbooking' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'bookingReport/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\BookingReportController@show',
        'controller' => 'App\\Http\\Controllers\\BookingReportController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'viewbooking',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'downloadExcel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\BookingReportController@downloadexcel',
        'controller' => 'App\\Http\\Controllers\\BookingReportController@downloadexcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'downloadExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadPDF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'downloadPDF',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\BookingReportController@downloadPDF',
        'controller' => 'App\\Http\\Controllers\\BookingReportController@downloadPDF',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'downloadPDF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberExpiry' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberExpiry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberExpiryController@index',
        'controller' => 'App\\Http\\Controllers\\SubscriberExpiryController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberExpiry',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'expirydownloadExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expirydownloadExcel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberExpiryController@downloadexcel',
        'controller' => 'App\\Http\\Controllers\\SubscriberExpiryController@downloadexcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'expirydownloadExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'expirydownloadPDF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'expirydownloadPDF',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberExpiryController@downloadpdf',
        'controller' => 'App\\Http\\Controllers\\SubscriberExpiryController@downloadpdf',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'expirydownloadPDF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberReport.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subscriberReport.index',
        'uses' => 'App\\Http\\Controllers\\SubscriberReportController@index',
        'controller' => 'App\\Http\\Controllers\\SubscriberReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberReport.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberReport/{subscriber}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'subscriberReport.show',
        'uses' => 'App\\Http\\Controllers\\SubscriberReportController@show',
        'controller' => 'App\\Http\\Controllers\\SubscriberReportController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'SubscriberdownloadExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'SubscriberdownloadExcel/{subscriber}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberReportController@downloadexcel',
        'controller' => 'App\\Http\\Controllers\\SubscriberReportController@downloadexcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'SubscriberdownloadExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'SubscriberdownloadPdf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'SubscriberdownloadPdf/{subscriber}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberReportController@downloadpdf',
        'controller' => 'App\\Http\\Controllers\\SubscriberReportController@downloadpdf',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'SubscriberdownloadPdf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'checking.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'checking',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'checking.store',
        'uses' => 'App\\Http\\Controllers\\CheckingController@store',
        'controller' => 'App\\Http\\Controllers\\CheckingController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'checking.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'checking/{checking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'checking.update',
        'uses' => 'App\\Http\\Controllers\\CheckingController@update',
        'controller' => 'App\\Http\\Controllers\\CheckingController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeeReport.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeeReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'employeeReport.index',
        'uses' => 'App\\Http\\Controllers\\EmployeeReportController@index',
        'controller' => 'App\\Http\\Controllers\\EmployeeReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeeReport.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeeReport/{admin}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'employeeReport.show',
        'uses' => 'App\\Http\\Controllers\\EmployeeReportController@show',
        'controller' => 'App\\Http\\Controllers\\EmployeeReportController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeeExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeeExcel/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\EmployeeReportController@downloadExcel',
        'controller' => 'App\\Http\\Controllers\\EmployeeReportController@downloadExcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'employeeExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeePdf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeePdf/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\EmployeeReportController@downloadPdf',
        'controller' => 'App\\Http\\Controllers\\EmployeeReportController@downloadPdf',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'employeePdf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeePerformance.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeePerformance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'employeePerformance.index',
        'uses' => 'App\\Http\\Controllers\\EmployeePerformanceController@index',
        'controller' => 'App\\Http\\Controllers\\EmployeePerformanceController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeePerformance.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeePerformance/{admin}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'employeePerformance.show',
        'uses' => 'App\\Http\\Controllers\\EmployeePerformanceController@show',
        'controller' => 'App\\Http\\Controllers\\EmployeePerformanceController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeePerformanceExcel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeePerformanceExcel/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\EmployeePerformanceController@downloadExcel',
        'controller' => 'App\\Http\\Controllers\\EmployeePerformanceController@downloadExcel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'employeePerformanceExcel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'employeePerformancePdf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'employeePerformancePdf/{employee}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\EmployeePerformanceController@downloadPdf',
        'controller' => 'App\\Http\\Controllers\\EmployeePerformanceController@downloadPdf',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'employeePerformancePdf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'enquiryComment.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'enquiryComment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'enquiryComment.store',
        'uses' => 'App\\Http\\Controllers\\EnquiryCommentController@store',
        'controller' => 'App\\Http\\Controllers\\EnquiryCommentController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'feed.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'feed',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'feed.index',
        'uses' => 'App\\Http\\Controllers\\FeedbackController@index',
        'controller' => 'App\\Http\\Controllers\\FeedbackController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'feed.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'feed/{feedback}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'feed.show',
        'uses' => 'App\\Http\\Controllers\\FeedbackController@show',
        'controller' => 'App\\Http\\Controllers\\FeedbackController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'report.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'report',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'report.index',
        'uses' => 'App\\Http\\Controllers\\ReportController@index',
        'controller' => 'App\\Http\\Controllers\\ReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'getChartData' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getchartdata',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriberHomeController@getChartData',
        'controller' => 'App\\Http\\Controllers\\SubscriberHomeController@getChartData',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'getChartData',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymenthistory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.index',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@index',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymenthistory/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.create',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@create',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'paymenthistory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.store',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@store',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymenthistory/{paymentDetail}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.show',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@show',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymenthistory/{paymentDetail}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.edit',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@edit',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'paymenthistory/{paymentDetail}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.update',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@update',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymenthistory.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'paymenthistory/{paymentDetail}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymenthistory.destroy',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@destroy',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicedownloadPDF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicedownloadPDF/{paymentDetail}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@downloadPDF',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@downloadPDF',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'invoicedownloadPDF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'getAppVerision' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getAppVerision/{site}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@appVerision',
        'controller' => 'App\\Http\\Controllers\\HomeController@appVerision',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'getAppVerision',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'updateAppVerision' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'updateAppVerision/{site}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@updateAppVerision',
        'controller' => 'App\\Http\\Controllers\\HomeController@updateAppVerision',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'updateAppVerision',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymentReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.index',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@index',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymentReport/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.create',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@create',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'paymentReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.store',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@store',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymentReport/{paymentReport}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.show',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@show',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'paymentReport/{paymentReport}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.edit',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@edit',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'paymentReport/{paymentReport}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.update',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@update',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paymentReport.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'paymentReport/{paymentReport}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'paymentReport.destroy',
        'uses' => 'App\\Http\\Controllers\\PaymentReportController@destroy',
        'controller' => 'App\\Http\\Controllers\\PaymentReportController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'voucher',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.index',
        'uses' => 'App\\Http\\Controllers\\VoucherController@index',
        'controller' => 'App\\Http\\Controllers\\VoucherController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'voucher/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.create',
        'uses' => 'App\\Http\\Controllers\\VoucherController@create',
        'controller' => 'App\\Http\\Controllers\\VoucherController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'voucher',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.store',
        'uses' => 'App\\Http\\Controllers\\VoucherController@store',
        'controller' => 'App\\Http\\Controllers\\VoucherController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'voucher/{voucher}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.show',
        'uses' => 'App\\Http\\Controllers\\VoucherController@show',
        'controller' => 'App\\Http\\Controllers\\VoucherController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'voucher/{voucher}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.edit',
        'uses' => 'App\\Http\\Controllers\\VoucherController@edit',
        'controller' => 'App\\Http\\Controllers\\VoucherController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'voucher/{voucher}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.update',
        'uses' => 'App\\Http\\Controllers\\VoucherController@update',
        'controller' => 'App\\Http\\Controllers\\VoucherController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'voucher.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'voucher/{voucher}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'voucher.destroy',
        'uses' => 'App\\Http\\Controllers\\VoucherController@destroy',
        'controller' => 'App\\Http\\Controllers\\VoucherController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupon',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.index',
        'uses' => 'App\\Http\\Controllers\\CouponController@index',
        'controller' => 'App\\Http\\Controllers\\CouponController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupon/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.create',
        'uses' => 'App\\Http\\Controllers\\CouponController@create',
        'controller' => 'App\\Http\\Controllers\\CouponController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'coupon',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.store',
        'uses' => 'App\\Http\\Controllers\\CouponController@store',
        'controller' => 'App\\Http\\Controllers\\CouponController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupon/{coupon}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.show',
        'uses' => 'App\\Http\\Controllers\\CouponController@show',
        'controller' => 'App\\Http\\Controllers\\CouponController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupon/{coupon}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.edit',
        'uses' => 'App\\Http\\Controllers\\CouponController@edit',
        'controller' => 'App\\Http\\Controllers\\CouponController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'coupon/{coupon}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.update',
        'uses' => 'App\\Http\\Controllers\\CouponController@update',
        'controller' => 'App\\Http\\Controllers\\CouponController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupon.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'coupon/{coupon}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'coupon.destroy',
        'uses' => 'App\\Http\\Controllers\\CouponController@destroy',
        'controller' => 'App\\Http\\Controllers\\CouponController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coupStatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coupStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\CouponController@couponstatus',
        'controller' => 'App\\Http\\Controllers\\CouponController@couponstatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'coupStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.banner.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/banner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.banner.index',
        'uses' => 'App\\Http\\Controllers\\bannerController@index',
        'controller' => 'App\\Http\\Controllers\\bannerController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.banner.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/banner/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.banner.create',
        'uses' => 'App\\Http\\Controllers\\bannerController@create',
        'controller' => 'App\\Http\\Controllers\\bannerController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.banner.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/banner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.banner.store',
        'uses' => 'App\\Http\\Controllers\\bannerController@store',
        'controller' => 'App\\Http\\Controllers\\bannerController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.banner.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/banner/{banner}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.banner.edit',
        'uses' => 'App\\Http\\Controllers\\bannerController@edit',
        'controller' => 'App\\Http\\Controllers\\bannerController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.banner.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/banner/{banner}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.banner.update',
        'uses' => 'App\\Http\\Controllers\\bannerController@update',
        'controller' => 'App\\Http\\Controllers\\bannerController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.banner.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/banner/{banner}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.banner.destroy',
        'uses' => 'App\\Http\\Controllers\\bannerController@destroy',
        'controller' => 'App\\Http\\Controllers\\bannerController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.info.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/info',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.info.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.info.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/info/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.info.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.info.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/info',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.info.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.info.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/info/{info}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.info.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.info.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/info/{info}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.info.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.info.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/info/{info}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.info.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.infoStatus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/infoStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\InfoController@changeStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\InfoController@changeStatus',
        'as' => 'admin.infoStatus',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies/{company}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'admin.companies.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.toggle-status' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/companies/{company}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@toggleStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@toggleStatus',
        'as' => 'admin.companies.toggle-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'subscriberWhatsappNotify' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subscriberWhatsappNotify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\NotifyController@subscriberWhatsappNotify',
        'controller' => 'App\\Http\\Controllers\\Admin\\NotifyController@subscriberWhatsappNotify',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'subscriberWhatsappNotify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'makePlatFormFee' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'makePlatFormFee',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Subscriber\\PaymentController@makePlatFormFee',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\PaymentController@makePlatFormFee',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'makePlatFormFee',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'successfulPlatFormFee' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'successfulPlatFormFee/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Subscriber\\PaymentController@successfulPlatFormFee',
        'controller' => 'App\\Http\\Controllers\\Subscriber\\PaymentController@successfulPlatFormFee',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'successfulPlatFormFee',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'getAccessTokenUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getAccessTokenUser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\FirebaseController@getAccessTokenUser',
        'controller' => 'App\\Http\\Controllers\\FirebaseController@getAccessTokenUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'getAccessTokenUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'getAccessTokenDriver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getAccessTokenDriver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\FirebaseController@getAccessTokenDriver',
        'controller' => 'App\\Http\\Controllers\\FirebaseController@getAccessTokenDriver',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'getAccessTokenDriver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'crypto.settings.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'crypto/settings/{site}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'crypto.settings.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\Crypto\\SettingsController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\Crypto\\SettingsController@edit',
        'namespace' => NULL,
        'prefix' => '/crypto',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'crypto.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'crypto/settings/{site}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'crypto.settings.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\Crypto\\SettingsController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\Crypto\\SettingsController@update',
        'namespace' => NULL,
        'prefix' => '/crypto',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'crypto.addCoins' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'crypto/add-coins/{site}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\Crypto\\SettingsController@addCoins',
        'controller' => 'App\\Http\\Controllers\\Admin\\Crypto\\SettingsController@addCoins',
        'as' => 'crypto.addCoins',
        'namespace' => NULL,
        'prefix' => '/crypto',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
