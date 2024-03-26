<?php

namespace Leantime\Domain\Timesheets\Controllers {

    use Leantime\Core\Controller;
    use Leantime\Domain\Auth\Models\Roles;
    use Leantime\Domain\Timesheets\Repositories\Timesheets as TimesheetRepository;
    use Leantime\Domain\Auth\Services\Auth;
    use Leantime\Core\Frontcontroller;

    /**
     *
     */
    class DelTime extends Controller
    {
        private TimesheetRepository $timesheetsRepo;

        /**
         * init - initialize private variable
         *
         * @access public
         */
        public function init(TimesheetRepository $timesheetsRepo)
        {
            $this->timesheetsRepo = $timesheetsRepo;
        }

        /**
         * run - display template and edit data
         *
         * @access public
         */
        public function run()
        {
            Auth::authOrRedirect([Roles::$owner, Roles::$admin, Roles::$manager, Roles::$editor], true);

            $msgKey = '';

            if (isset($_GET['id']) === true) {
                $id = (int)($_GET['id']);

                //Delete User
                if (isset($_POST['del']) === true) {
                    $this->timesheetsRepo->deleteTime($id);

                    $this->tpl->setNotification("notifications.time_deleted_successfully", "success");

                    if (isset($_SESSION['lastPage'])) {
                        return Frontcontroller::redirect($_SESSION['lastPage']);
                    } else {
                        return Frontcontroller::redirect(BASE_URL . "/timsheets/showMyList");
                    }
                }

                $this->tpl->assign("id", $id);
                return $this->tpl->displayPartial('timesheets.delTime');
            } else {
                return $this->tpl->displayPartial('errors.error403');
            }
        }
    }
}
