<?php

namespace Leantime\Domain\Auth\Controllers {

    use Leantime\Core\Controller;
    use Leantime\Domain\Auth\Services\Auth as AuthService;
    use Symfony\Component\HttpFoundation\JsonResponse;

    /**
     *
     */
    class KeepAlive extends Controller
    {
        private AuthService $authService;

        /**
         * init - initialize private variables
         *
         * @access public
         * @params parameters or body of the request
         */
        public function init(AuthService $authService)
        {
            $this->authService = $authService;
        }


        /**
         * get - handle get requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function get($params)
        {

            $userId = $_SESSION['userdata']['id'];
            $sessionId = $this->authService->getSessionId();

            //TODO: Once we have a session table, check the session is valid in there as well as
            // added security layer. If not we can log the user out.
            $return = $this->authService->updateUserSessionDB($userId, $sessionId);

            $response = array("status" => "ok");
            if ($return) {
                return new JsonResponse($response);
            } else {
                $response["status"] = "logout";
                return new JsonResponse($response);
            }
        }
    }

}
