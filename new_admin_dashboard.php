<style>
    .ongoingsearch_wrap {
        max-height: 400px;
        overflow: hidden;
        overflow-y: scroll;
        width: 100%;
    }

    .ongoingsearch_wrap::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .ongoingsearch_wrap::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    .ongoingsearch_wrap::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #2ba9e1;
    }

    .searchonGoingProjects {
        border-radius: 30px;
        padding-left: 25px;
    }

    .scroll-projectDetails {
        overflow-y: scroll !important;
    }

    .scroll-projectDetails::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    .scroll-projectDetails::-webkit-scrollbar {
        width: 5px;
        background-color: #F5F5F5;
    }

    .scroll-projectDetails::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #2ba9e1;
    }
</style>
<?php

$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $daily_working_hour[0]->working_hour);

sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

$task_time = $time_seconds * 2;
$duration_time = strtotime($finished_soon[0]['duration_time']);

// echo "<pre>";
// print_r($working_hours);die;
?>
<input type="hidden" id="birthdate" name="birthdate"
    value="<?= date('m-d', strtotime($_SESSION['valid_login']['birth_date'])) ?>">
<input type="hidden" id="todaysDate" name="todaysDate" value="<?= date('m-d') ?>">
<p style="display: none;" id="user_name"><?= $_SESSION['valid_login']['username'] ?></p>
<section class="new-dashboard-main new-admin-dashboard">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="total-task-wrap-main new-hr-dashboard">
                            <div class="row">
                                <div class="col-xl-6 col-lg-3 col-md-3 col-sm-6">
                                    <div class="new-cmn-box total-task-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="dashboard-profile-wrap" <?php if (date('d-m', strtotime(DATE_TIME)) == date('d-m', strtotime($_SESSION['valid_login']['birth_date']))) { ?> data-toggle="modal" data-target="#birthday_post" <?php } ?>>
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>public/upload/user/<?= ($_SESSION['valid_login']['image'] != '') ? $_SESSION['valid_login']['image'] : 'user.jpg' ?>?>"
                                                    alt="profile" />
                                            </div>
                                            <div class="profile-name">
                                                <h4><?php if (date('d-m', strtotime(DATE_TIME)) == date('d-m', strtotime($_SESSION['valid_login']['birth_date']))) {
                                                    echo "Happy Birthday";
                                                } elseif (DATE_TIME < date('Y-m-d 12:00:00')) {
                                                    echo "Good Morning";
                                                } elseif (DATE_TIME < date('Y-m-d 17:00:00')) {
                                                    echo "Good Afternoon";
                                                } else {
                                                    echo "Good Evening";
                                                } ?>,
                                                    <?= $_SESSION['valid_login']['username'] ?>
                                                    <img class="lazy"
                                                        data-src="<?= base_url() ?>public/assets/img/smile.png"
                                                        alt="smile" />
                                                </h4>
                                                <p><?php foreach ($empTech as $key => $tech) {
                                                    echo " " . $tech->tech . " ";
                                                } ?>admin
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                                    <div class="new-cmn-box total-task-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="total-task-img-wrap total-project">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/calendar.svg"
                                                    alt="calendar" />
                                            </div>
                                            <div class="total-project-text">
                                                <p class="semi-14">Attendance</p>
                                                <h5 class="semi-24"><?= $count_emp ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                                    <div class="new-cmn-box total-task-wrap">
                                        <a href="<?= base_url() . 'leave_application/leave_filter/pending' ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="total-task-img-wrap total-project pending-task">
                                                    <img class="lazy"
                                                        data-src="<?= base_url() ?>/public/assets/img/calendar.svg"
                                                        alt="calendar" />
                                                </div>
                                                <div class="total-project-text">
                                                    <p class="semi-14">Pending Leave</p>
                                                    <h5 class="semi-24"><?= $pending ?></h5>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6">
                        <div class="projects-main-wrap new-cmn-box height-507 projectDetails scroll-projectDetails">
                            <div class="total-task-wrap-main">
                                <div class="new-cmn-box total-task-wrap">
                                    <div class="d-flex align-items-center">
                                        <a href="<?= base_url() . 'project' ?>">
                                            <div class="total-task-img-wrap total-project">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/total-project.svg"
                                                    alt="">
                                            </div>
                                        </a>
                                        <div class="total-project-text">
                                            <p class="cmn-p">Total Project(s)</p>
                                            <h5 class="cmn-h5"><?= $count_project ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($pending_approval_project)) { ?>
                                <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                                    <div style="cursor:pointer" data-toggle="modal"
                                        data-target="#pennding_project_for_approval"
                                        class="d-flex justify-content-between align-items-start">
                                        <h6>Pending For Approval Project(s) (<?= count($pending_approval_project) ?>) </h6>
                                        <div class="modal-arrow-wrap">
                                            <button class="cmn-modal-btn" data-toggle="modal"
                                                data-target="#pennding_project_for_approval"><img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                                    alt="arrow"></button>
                                        </div>
                                    </div>
                                    <div class="">
                                        <input id="four" type="range" value="75" min="0" max="100" step="1"
                                            style="display: none;">

                                        <div
                                            class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                                            <a href="<?= base_url() . 'project/edit_project/' . $this->utility->safe_b64encode($pending_approval_project[0]->id) ?>"
                                                class="edit_p_btn">
                                                <div class="d-flex align-items-center">
                                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                                        <img class="lazy"
                                                            data-src="<?= base_url() ?>/public/upload/project/<?= ($pending_approval_project[$i]->project_image != '') ? $pending_approval_project[$i]->project_image : 'project.png' ?>"
                                                            alt="project" />
                                                    </div>
                                                    <div>
                                                        <h5 class="semi-16"><?= $pending_approval_project[0]->project_name ?>
                                                        </h5>
                                                        <p class="med-14 mt-2">Project Lead -
                                                            <?= $pending_approval_project[0]->username ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                            <div>
                                                <div class="pie-4"
                                                    data-pie='{ "lineargradient": ["#B85CFF"], "round": true, "percent": <?= $pending_approval_project[0]->progress ?> , "colorCircle": "#e6e6e6" }'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($onGoingProjects)) { ?>
                                <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                                    <div style="cursor:pointer" data-toggle="modal" data-target="#onGoingProjects"
                                        class="d-flex justify-content-between align-items-start">
                                        <h6>On going project(s) (<?= count($onGoingProjects) ?>)</h6>
                                        <div class="modal-arrow-wrap">
                                            <button class="cmn-modal-btn" data-toggle="modal"
                                                data-target="#onGoingProjects"><img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                                    alt="arrow"></button>
                                        </div>
                                    </div>

                                    <div>
                                        <?php for ($i = 0; $i < count($onGoingProjects); $i++) { ?>

                                            <div
                                                class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                                                <a
                                                    href="<?= base_url() . 'project/project_view/' . $this->utility->safe_b64encode($onGoingProjects[$i]->id) ?>">
                                                    <div class="d-flex align-items-center">
                                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                                            <img class="lazy"
                                                                data-src="<?= base_url() ?>/public/upload/project/<?= ($onGoingProjects[$i]->project_image != '') ? $onGoingProjects[$i]->project_image : 'project.png' ?>"
                                                                alt="project" />
                                                        </div>
                                                        <div>
                                                            <h5 class="semi-16"><?= $onGoingProjects[$i]->project_name ?></h5>
                                                            <p class="med-14 mt-2">Project Lead -
                                                                <?= $onGoingProjects[$i]->username ?></p>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div>
                                                    <div class="pie-4"
                                                        data-pie='{ "lineargradient": ["#B85CFF"], "round": true, "percent": <?= $onGoingProjects[$i]->progress; ?>, "colorCircle": "#e6e6e6" }'>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($i == '1') {
                                                break;
                                            }
                                        } ?>
                                    </div>
                                </div>
                            <?php }
                            if (!empty($outOfDeadline)) { ?>
                                <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                                    <div style="cursor:pointer" data-toggle="modal" data-target="#outOfDeadline"
                                        class="d-flex justify-content-between align-items-start">
                                        <h6>Out of Deadline Project(s) (<?= count($outOfDeadline) ?>) </h6>
                                        <div class="modal-arrow-wrap">
                                            <button class="cmn-modal-btn" data-toggle="modal"
                                                data-target="#outOfDeadline"><img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                                    alt="arrow"></button>
                                        </div>
                                    </div>
                                    <div class="">
                                        <input id="four" type="range" value="75" min="0" max="100" step="1"
                                            style="display: none;">

                                        <div
                                            class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                                            <a
                                                href="<?= base_url() . 'project/project_view/' . $this->utility->safe_b64encode($outOfDeadline[0]->id) ?>">
                                                <div class="d-flex align-items-center">
                                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                                        <img class="lazy"
                                                            data-src="<?= base_url() ?>/public/upload/project/<?= ($outOfDeadline[$i]->project_image != '') ? $outOfDeadline[$i]->project_image : 'project.png' ?>"
                                                            alt="project" />
                                                    </div>
                                                    <div>
                                                        <h5 class="semi-16"><?= $outOfDeadline[0]->project_name ?></h5>
                                                        <p class="med-14 mt-2">Project Lead -
                                                            <?= $outOfDeadline[0]->username ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                            <div>
                                                <div class="pie-4"
                                                    data-pie='{ "lineargradient": ["#B85CFF"], "round": true, "percent": <?= $outOfDeadline[0]->progress ?> , "colorCircle": "#e6e6e6" }'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6">
                        <div
                            class="new-cmn-box timesheet-wrap new-hr-dashboard admin-dashboard-late-punchin going-project on-going-project height-507">


                            <div class="">
                                <div style="cursor:pointer" data-toggle="modal" data-target="#todayLate"
                                    class="d-flex justify-content-between align-items-start">
                                    <h6>Late Punch In (<?= count($todayLateuserShow) ?>)</h6>
                                    <div class="modal-arrow-wrap">
                                        <button class="cmn-modal-btn" data-toggle="modal" data-target="#todayLate"><img
                                                class="lazy" data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                                alt="arrow"></button>
                                    </div>
                                </div>
                                <div class="admin-dashboard-leave-main">
                                    <?php if (!empty($todayLateuserShow)) { ?>
                                        <?php for ($i = 0; $i < count($todayLateuserShow); $i++) { ?>
                                            <div class="leave-wrap d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                                        <a
                                                            href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($todayLateuserShow[$i][0]->id) ?>"><img
                                                                alt="" class="lazy"
                                                                data-src="<?= base_url() . 'public/upload/user/' . $todayLateuserShow[$i][0]->image ?>"></a>
                                                    </div>
                                                    <div>
                                                        <h5 class="semi-16">
                                                            <?= character_limiter($todayLateuserShow[$i][0]->username, 10) ?>
                                                        </h5>
                                                        <p class="med-12 mt-2"><?= $todayLateuserShow[$i][0]->tech ?> </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="in-time">
                                                        <?= date('H:i', strtotime($todayLateuserShow[$i][0]->punch_datetime)) ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php if ($i == '1') {
                                                break;
                                            }
                                        } ?>

                                    <?php } ?>
                                </div>
                            </div>



                            <div class="not-completed-detail-admin">
                                <div class="d-flex justify-content-between align-items-start" style="cursor:pointer"
                                    data-toggle="modal" data-target="#notCompleteHour">
                                    <h6 id="displayCount"></h6>
                                    <div class="modal-arrow-wrap">
                                        <button class="cmn-modal-btn" data-toggle="modal"
                                            data-target="#notCompleteHour"><img class="lazy"
                                                data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                                alt="arrow"></button>
                                    </div>
                                </div>
                                <div class="admin-dashboard-leave-main">
                                    <?php if (DATE_TIME >= date('Y-m-d H:i:s', strtotime($set_hour[0]->working_hour))) { ?>
                                        <?php
                                        $totalHour = date("H.i", strtotime($daily_working_hour[0]->working_hour));
                                        $half_day = date("H.i", strtotime($half_day_working_hour[0]->working_hour));

                                        // $totalHour = '08:45:00';
                                        // $half_day = '04:30:00';
                                        //print_r($half_day_leave);
                                    
                                        for ($i = 0; $i < count($working_hours); $i++) {
                                            if ($working_hours[$i]['hour'] <= $totalHour) {
                                                foreach ($half_day_leave as $key => $halfLeave) {
                                                    if ($halfLeave->user_id == $working_hours[$i][0]->id && $halfLeave->from == date("Y-m-d") && $halfLeave->type == '3' && $halfLeave->status == '1') {
                                                        unset($working_hours[$i]);
                                                        $working_hours = array_values($working_hours);
                                                    }
                                                }
                                                ?>
                                                <div class="leave-wrap d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                                            <img class="lazy"
                                                                data-src="<?= base_url() . 'public/upload/user/' . $working_hours[$i][0]->image ?>"
                                                                alt="festival-img" />
                                                        </div>
                                                        <div>
                                                            <h5 class="semi-16"><?= $working_hours[$i][0]->username ?></h5>
                                                            <p class="med-12 mt-2"><?= $working_hours[$i][0]->tech ?></p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="in-time"><?= $working_hours[$i]['hour'] ?></p>
                                                    </div>
                                                </div>
                                                <?php if ($i == '1') {
                                                    break;
                                                }
                                            }
                                        } ?>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="thought-of-day">
                            <?php if ($getThought[0]->image != '') { ?>
                                <div class="thought-of-day-img-wrap " data-toggle="modal" data-target="#thought">
                                    <img class="lazy"
                                        data-src="<?= base_url() . 'public/upload/thought/' . $getThought[0]->image ?>">
                                </div>
                            <?php } else { ?>
                                <div class="yellow-circle1 y-circle"></div>
                                <div class="yellow-circle2 y-circle"></div>
                                <div class="yellow-circle3 y-circle"></div>
                                <p><?= $getThought[0]->thought ?>
                                    <!-- “Don't Take rest after your first victory becuase 
                            if you fail in second, More lips are waiting to say that
                            your first victory was just luck” -->
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div
                            class="new-cmn-box timesheet-wrap hr-statistics task-statistics-main new-admin-statistics height-450">
                            <div>
                                <h6>Task Statistics</h6>

                                <div class="chart-wrap">
                                    <div id="doughnutChart" class="chart"></div>
                                </div>

                                <div class="task-statistics">
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap new-tasks">
                                            <a
                                                href="<?= base_url() . 'project/project_complete_task/' . $this->utility->safe_b64encode('0') ?>">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/new-task.svg" alt="">
                                            </a>
                                        </div>
                                        <div>
                                            <h5 class="semi-18 new_task"><?= count($new_task) ?></h5>
                                            <p>New</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap completed-task">
                                            <a
                                                href="<?= base_url() . 'project/project_complete_task/' . $this->utility->safe_b64encode('2') ?>">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/equalizer.svg" alt="">
                                            </a>
                                        </div>
                                        <div>
                                            <h5 class="semi-18 completed_task"><?= count($completed_task) ?></h5>
                                            <p>Completed</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap pending-task">
                                            <a
                                                href="<?= base_url() . 'project/project_complete_task/' . $this->utility->safe_b64encode('3') ?>">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/library.svg" alt="">
                                            </a>
                                        </div>
                                        <div>
                                            <h5 class="semi-18 pending_task"><?= count($pending_task) ?></h5>
                                            <p>Pending</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap approved-task">
                                            <a
                                                href="<?= base_url() . 'project/project_complete_task/' . $this->utility->safe_b64encode('5') ?>">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/approved-task.svg"
                                                    alt="">
                                            </a>
                                        </div>
                                        <div>
                                            <h5 class="semi-18 approved_task"><?= count($approved_task) ?></h5>
                                            <p>Approved</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap working-task">
                                            <a
                                                href="<?= base_url() . 'project/project_complete_task/' . $this->utility->safe_b64encode('1') ?>">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/barcode-read.svg"
                                                    alt="">
                                            </a>
                                        </div>
                                        <div>
                                            <h5 class="semi-18 working_task"><?= count($working_task) ?></h5>
                                            <p>Working</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap reopen-task">
                                            <a
                                                href="<?= base_url() . 'project/project_complete_task/' . $this->utility->safe_b64encode('4') ?>">
                                                <img class="lazy"
                                                    data-src="<?= base_url() ?>/public/assets/img/reopen-task.svg" alt="">
                                            </a>
                                        </div>
                                        <div>
                                            <h5 class="semi-18 reopen_task"><?= count($reopen_task) ?></h5>
                                            <p>Reopen</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap ending-soon-task" data-toggle="modal"
                                            data-target="#finished_soon">
                                            <!-- <a href="<?= base_url() . 'project/project_complete_task/working' ?>"></a> -->
                                            <img class="lazy"
                                                data-src="<?= base_url() ?>/public/assets/img/ending-soon.svg" alt="">
                                        </div>
                                        <div>
                                            <h5 class="semi-18 finished_soon"></h5>
                                            <p>Ending soon</p>
                                        </div>
                                    </div>
                                    <div class="task-statistics-inner">
                                        <div class="task-img-wrap task-needed-task" data-toggle="modal"
                                            data-target="#task_needed">
                                            <!-- <a href="<?= base_url() . 'project/project_complete_task/reopen' ?>"></a> -->
                                            <img class="lazy"
                                                data-src="<?= base_url() ?>/public/assets/img/task-needed.svg" alt="">
                                        </div>
                                        <div>
                                            <h5 class="semi-18 task_needed"><?= count($task_needed) ?></h5>
                                            <p>Task needed</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="new-cmn-box upcoming-holidays upcoming-holidays-main todays-update-main height-288 mb-xl-0">
                    <div style="cursor:pointer" data-toggle="modal" data-target="#upcomingHoliday"
                        class="d-flex justify-content-between align-items-start">
                        <h6>Upcoming Holidays (<?= $upcoming_holiday ? count($upcoming_holiday) : '' ?>)</h6>
                        <div class="modal-arrow-wrap">
                            <button class="cmn-modal-btn" data-toggle="modal" data-target="#upcomingHoliday"><img
                                    class="lazy" data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                    alt="arrow"></button>
                        </div>
                    </div>

                    <div class="leave-wrap d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="new-cmn-btn mt-0 p-0 border-0">
                                <a href="https://cmexpertiseinfotech.com/new_erp/employee/profile/OTA">
                                    <img alt="" class="" data-src="https://cmexpertiseinfotech.com/new_erp/public/upload/user/image_1718080921.png" src="https://cmexpertiseinfotech.com/new_erp/public/upload/user/image_1718080921.png">
                                </a>
                            </div>
                            <div>
                                <a href="https://cmexpertiseinfotech.com/new_erp/employee/profile/OTA">
                                    <h5 class="semi-16">
                                        <p>
                                            Jayesh</p>

                                    </h5>
                                </a>
                                <p class="med-12 mt-2">PHP </p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($upcoming_holiday)) { ?>
                        <?php for ($i = 0; $i < count($upcoming_holiday); $i++) { ?>
                            <div class="leave-wrap d-flex align-items-center">
                                <div>
                                    <h5 class="semi-16"><?= $upcoming_holiday[$i][0]->day ?></h5>
                                    <p class="med-14"><?= date('l d M Y', strtotime($upcoming_holiday[$i][0]->date)) ?></p>
                                </div>
                            </div>
                            <?php if ($i == '1') {
                                break;
                            }
                        } ?>
                    <?php } ?>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="new-cmn-box upcoming-holidays upcoming-birthday  height-288 mb-xl-0">
                    <div style="cursor:pointer" data-toggle="modal" data-target="#upcomingBirthday"
                        class="d-flex justify-content-between align-items-start">
                        <h6>Upcoming Birthdays (<?= count($upcoming_birth_day) ?>)</h6>
                        <div class="modal-arrow-wrap">
                            <button class="cmn-modal-btn" data-toggle="modal" data-target="#upcomingBirthday"><img
                                    class="lazy" data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                    alt="arrow"></button>
                        </div>
                    </div>
                    <?php $i = 0;
                    foreach ($upcoming_birth_day as $key => $value):
                        $i++ ?>
                        <div class="leave-wrap d-flex align-items-center justify-content-between">
                            <a
                                href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value->user_id) ?>">
                                <div class="d-flex align-items-center">
                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                        <?php if ($value->image != '') { ?>
                                            <img alt="" class="lazy"
                                                data-src="<?= base_url() . 'public/upload/user/' . $value->image ?>">
                                        <?php } else { ?>
                                            <img alt="" class="lazy"
                                                data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                        <?php } ?>
                                    </div>
                                    <div>
                                        <h5 class="semi-16"><?= $value->username ?></h5>
                                        <!-- <p class="med-12 mt-2"><?= $value->tech ?> Developer</p> -->
                                        <p class="med-12 mt-2"><?= date('jS M', strtotime($value->birth_date)) ?></p>
                                    </div>
                                </div>
                            </a>
                            <div>

                                <a
                                    href="<?= base_url() ?>birthday_post?b_id=<?= $this->utility->safe_b64encode($value->user_id) ?>">
                                    <?php //if($value->birthday_card != ''){ ?>
                                    <!-- <img class="lazy" data-src="<?= base_url() ?>public/birthday_post/upload/<?= $value->birthday_card ?>" class="cake-img" alt="cake" /> -->
                                    <?php //}else{ ?>
                                    <img class="lazy" data-src="<?= base_url() ?>public/assets/img/cake.png" class="cake-img"
                                        alt="cake" />
                                    <?php //} ?>
                                </a>
                            </div>
                        </div>
                        <?php if ($i == '2') {
                            break;
                        } ?>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div
                    class="new-cmn-box upcoming-holidays upcoming-birthday puchin-leaves-absents-detail todays-update-main height-288 mb-xl-0">
                    <div>
                        <div style="cursor:pointer" data-toggle="modal" data-target="#todayUpdate"
                            class="d-flex justify-content-between align-items-start">
                            <h6>Today's Absent (<?= count($todayAbsentuserShow) ?>)</h6>
                            <div class="modal-arrow-wrap">
                                <button class="cmn-modal-btn" data-toggle="modal" data-target="#todayUpdate"><img
                                        class="lazy" data-src="<?= base_url() ?>/public/assets/img/arrow.svg"
                                        alt="arrow"></button>
                            </div>
                        </div>
                        <?php if (!empty($todayAbsentuserShow) || !empty($onLeave)) { ?>
                            <?php if (!empty($todayAbsentuserShow) && !empty($onLeave)) { ?>
                                <div class="leave-wrap d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <a
                                            href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($todayAbsentuserShow[0]->id) ?>">
                                            <div class="new-cmn-btn mt-0 p-0 border-0">
                                                <?php if ($todayAbsentuserShow[0]->image != '') { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/' . $todayAbsentuserShow[0]->image ?>">
                                                <?php } else { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                                <?php } ?>
                                            </div>
                                        </a>
                                        <div>
                                            <h5 class="semi-16"><?= $todayAbsentuserShow[0]->username ?></h5>
                                            <p class="med-12 mt-2"><?= $todayAbsentuserShow[0]->tech ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="in-time for-desktop">Absent</p>
                                        <!-- <p class="in-time for-res">A</p> -->
                                    </div>
                                </div>
                                <div class="leave-wrap d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <?php if ($onLeave[0]->image != '') { ?>
                                                <img alt="" class="lazy"
                                                    data-src="<?= base_url() . 'public/upload/user/' . $onLeave[0]->image ?>">
                                            <?php } else { ?>
                                                <img alt="" class="lazy"
                                                    data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                            <?php } ?>
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $onLeave[0]->username ?></h5>
                                            <p class="med-12 mt-2"><?= $onLeave[0]->tech ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="pending-leave for-desktop">On Leave</p>
                                        <p class="pending-leave for-res">L</p>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <?php $j = 0;
                                foreach ($todayAbsentuserShow as $key => $value) {
                                    $j++; ?>
                                    <div class="leave-wrap d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <a
                                                href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value->id) ?>">
                                                <div class="new-cmn-btn mt-0 p-0 border-0">
                                                    <?php if ($value->image != '') { ?>
                                                        <img alt="" class="lazy"
                                                            data-src="<?= base_url() . 'public/upload/user/' . $value->image ?>">
                                                    <?php } else { ?>
                                                        <img alt="" class="lazy"
                                                            data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                                    <?php } ?>
                                                </div>
                                            </a>
                                            <div>
                                                <h5 class="semi-16"><?= $value->username ?></h5>
                                                <p class="med-12 mt-2"><?= $value->tech ?></p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="in-time for-desktop">Absent</p>
                                            <!-- <p class="in-time for-res">A</p> -->
                                        </div>
                                    </div>
                                    <?php if ($j == '2') {
                                        break;
                                    }
                                } ?>
                            <?php } ?>

                        <?php } ?>
                    </div>
                    <!-- </div> -->


                </div>
            </div>
        </div>
    </div>
</section>




<!-- The Modal -->


<!-- Completed Project Model -->

<div class="modal view-detail-modal fade" id="completed">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Completed Project(s)</h6>

                <div class="row ongoingsearch_wrap">
                    <input id="four" type="range" value="75" min="0" max="100" step="1" style="display: none;">
                    <?php foreach ($completeProjects as $key => $completed): ?>
                        <div class="col-lg-6">
                            <div class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">

                                <div class="d-flex align-items-center">
                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                        <img class="lazy"
                                            data-src="<?= base_url() ?>/public/upload/project/<?= $completed->project_image ?>"
                                            alt="project" />
                                    </div>
                                    <div>
                                        <h5 class="semi-16"><?= $completed->project_name ?></h5>
                                        <p class="med-14 mt-2">Project Lead - <?= $completed->username ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Ongoing Project Model -->


<div class="modal view-detail-modal fade" id="onGoingProjects">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-4">On going project(s) (<?= count($onGoingProjects) ?>)</h6>

                <!-- <div class="row"> -->
                <input type="text" placeholder="search here" class="searchonGoingProjects form-control mb-3">
                <div class="ongoingSearch ongoingsearch_wrap row">
                    <?php foreach ($onGoingProjects as $key => $value): ?>
                        <div class="col-lg-6">
                            <div class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                                <a
                                    href="<?= base_url() . 'project/project_view/' . $this->utility->safe_b64encode($value->id) ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <img class="lazy"
                                                data-src="<?= base_url() ?>/public/upload/project/<?= ($value->project_image != '') ? $value->project_image : 'project.png' ?>"
                                                alt="project" />
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $value->project_name ?></h5>
                                            <p class="med-14 mt-2">Project Lead - <?= $value->username ?></p>
                                        </div>
                                    </div>
                                </a>
                                <div>
                                    <div class="pie-4"
                                        data-pie='{ "lineargradient": ["#B85CFF"], "round": true, "percent": <?= $value->progress; ?>, "colorCircle": "#e6e6e6" }'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>


<!-- Out Of Deadline Project Model -->


<div class="modal view-detail-modal fade" id="outOfDeadline">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Out of Deadline Project(s) (<?= count($outOfDeadline) ?>) </h6>

                <div class="row ongoingsearch_wrap">
                    <?php foreach ($outOfDeadline as $key => $value): ?>
                        <div class="col-lg-6">
                            <div class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                                <a
                                    href="<?= base_url() . 'project/project_view/' . $this->utility->safe_b64encode($value->id) ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <img class="lazy"
                                                data-src="<?= base_url() ?>/public/upload/project/<?= ($value->project_image != '') ? $value->project_image : 'project.png' ?>"
                                                alt="project" />
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $value->project_name ?></h5>
                                            <p class="med-14 mt-2">Project Lead - <?= $value->username ?></p>
                                        </div>
                                    </div>
                                </a>
                                <div>
                                    <div class="pie-4"
                                        data-pie='{ "lineargradient": ["#B85CFF"], "round": true, "percent": <?= $value->progress; ?>, "colorCircle": "#e6e6e6" }'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal view-detail-modal fade" id="pennding_project_for_approval">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Pending For Approval Project(s) (<?= count($pending_approval_project) ?>)</h6>

                <div class="row ongoingsearch_wrap">
                    <?php foreach ($pending_approval_project as $key => $value): ?>
                        <div class="col-lg-6">
                            <div class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                                <a href="<?= base_url() . 'project/edit_project/' . $this->utility->safe_b64encode($value->id) ?>"
                                    class="edit_p_btn">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <img class="lazy"
                                                data-src="<?= base_url() ?>/public/upload/project/<?= ($value->project_image != '') ? $value->project_image : 'project.png' ?>"
                                                alt="project" />
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $value->project_name ?></h5>
                                            <p class="med-14 mt-2">Project Lead - <?= $value->username ?></p>
                                        </div>
                                    </div>
                                </a>
                                <div>
                                    <div class="pie-4"
                                        data-pie='{ "lineargradient": ["#B85CFF"], "round": true, "percent": <?= $value->progress; ?>, "colorCircle": "#e6e6e6" }'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Update Project Model -->

<div class="modal view-detail-modal view-detail-modal-small fade" id="todayUpdate">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Today's Absent</h6>

                <div class="row view-detail-inner">
                    <div class="col-lg-12">
                        <?php foreach ($todayAbsentuserShow as $key => $value): ?>
                            <div class="leave-wrap d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                        <a
                                            href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value->id) ?>">
                                            <?php if ($value->image != '') { ?>
                                                <img alt="" class="lazy"
                                                    data-src="<?= base_url() . 'public/upload/user/' . $value->image ?>"></a>
                                        <?php } else { ?>
                                            <img alt="" class="lazy"
                                                data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>"></a>
                                        <?php } ?>
                                    </div>
                                    <div>
                                        <h5 class="semi-16"><?= $value->username ?></h5>
                                        <p class="med-12 mt-2"><?= $value->tech ?></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="in-time">Absent</p>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <?php foreach ($onLeave as $key => $value): ?>
                            <div class="leave-wrap d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                        <a
                                            href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value->user_id) ?>">
                                            <?php if ($value->image != '') { ?>
                                                <img alt="" class="lazy"
                                                    data-src="<?= base_url() . 'public/upload/user/' . $value->image ?>"></a>
                                        <?php } else { ?>
                                            <img alt="" class="lazy"
                                                data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>"></a>
                                        <?php } ?>
                                    </div>
                                    <div>
                                        <h5 class="semi-16"><?= $value->username ?></h5>
                                        <p class="med-12 mt-2"><?= $value->tech ?></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="pending-leave">On Leave</p>
                                </div>
                            </div>
                        <?php endforeach ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Birthday Model -->

<div class="modal view-detail-modal view-detail-modal-small fade" id="upcomingBirthday">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Upcoming Birthdays</h6>
                <div class="row view-detail-inner">
                    <div class="col-lg-12">
                        <?php foreach ($upcoming_birth_day as $key => $value): ?>
                            <div class="leave-wrap d-flex align-items-center justify-content-between">
                                <a
                                    href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value->user_id) ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <div>
                                                <?php if ($value->image != '') { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/' . $value->image ?>">
                                                <?php } else { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $value->username ?></h5>
                                            <!-- <p class="med-12 mt-2"><?= $value->tech ?> Developer</p> -->
                                            <p class="med-12 mt-2"><?= date('jS M', strtotime($value->birth_date)) ?></p>
                                        </div>
                                    </div>
                                </a>
                                <div>
                                    <a
                                        href="<?= base_url() ?>birthday_post?b_id=<?= $this->utility->safe_b64encode($value->user_id) ?>">
                                        <?php if ($value->birthday_card != '') { ?>
                                            <img class="lazy"
                                                data-src="<?= base_url() ?>public/birthday_post/upload/<?= $value->birthday_card ?>"
                                                class="cake-img" alt="cake" />
                                        <?php } else { ?>
                                            <img class="lazy" data-src="<?= base_url() ?>public/assets/img/cake.png"
                                                class="cake-img" alt="cake" />
                                        <?php } ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Late Punch In Model -->
<?php if (!empty($todayLateuserShow)) { ?>
    <div class="modal view-detail-modal view-detail-modal-small fade" id="todayLate">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                    <h6 class="mb-0">Late Punch In</h6>

                    <div class="row view-detail-inner">
                        <div class="col-lg-12">
                            <?php for ($i = 0; $i < count($todayLateuserShow); $i++) { ?>
                                <div class="leave-wrap d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <div>
                                                <?php if ($todayLateuserShow[$i][0]->image != '') { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/' . $todayLateuserShow[$i][0]->image ?>">
                                                <?php } else { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $todayLateuserShow[$i][0]->username ?></h5>
                                            <p class="med-12 mt-2"><?= $todayLateuserShow[$i][0]->tech ?> </p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="in-time">
                                            <?= date('H:i', strtotime($todayLateuserShow[$i][0]->punch_datetime)) ?></p>
                                    </div>
                                </div>
                                <!--   </div> -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Not completed working hours Model -->
<?php $j = 0;
if (DATE_TIME >= date('Y-m-d H:i:s', strtotime($set_hour[0]->working_hour))) { ?>
    <div class="modal view-detail-modal view-detail-modal-small fade" id="notCompleteHour">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                    <h6 class="mb-0">Not completed working hours</h6>

                    <div class="row view-detail-inner">
                        <div class="col-lg-12">
                            <?php
                            //  echo "<pre>"; print_r($half_day_leave);die;
                            $totalHour = date("H.i", strtotime($daily_working_hour[0]->working_hour));
                            $half_day = date("H.i", strtotime($half_day_working_hour[0]->working_hour));

                            // $totalHour = '08:45:00';
                            // $half_day = '04:30:00';
                            //print_r($half_day_leave);
                            for ($i = 0; $i < count($working_hours); $i++) {
                                if ($working_hours[$i]['hour'] <= $half_day) {
                                    foreach ($half_day_leave as $key => $halfLeave) {
                                        if ($halfLeave->user_id == $working_hours[$i][0]->id && $halfLeave->from == date("Y-m-d") && $halfLeave->type == '3') { ?>

                                            <div class="leave-wrap d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="new-cmn-btn mt-0 p-0 border-0">
                                                        <?php if ($working_hours[$i][0]->image != '') { ?>
                                                            <img class="lazy"
                                                                data-src="<?= base_url() . 'public/upload/user/' . $working_hours[$i][0]->image ?>"
                                                                alt="festival-img" />
                                                        <?php } else { ?>
                                                            <img class="lazy" data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>"
                                                                alt="festival-img" />
                                                        <?php } ?>
                                                    </div>
                                                    <div>
                                                        <h5 class="semi-16"><?= $working_hours[$i][0]->username ?></h5>
                                                        <p class="med-12 mt-2"><?= $working_hours[$i][0]->tech ?> </p>
                                                        <p class="med-12 mt-2 text-danger">Half Day Leave</p>
                                                    </div>

                                                </div>
                                                <div>
                                                    <p class="in-time">working hour :- <?= $working_hours[$i]['hour'] ?></p>
                                                </div>
                                            </div>
                                            <?php
                                            unset($working_hours[$i]);
                                            $working_hours = array_values($working_hours);

                                        }
                                    }
                                }
                            }

                            for ($i = 0; $i < count($working_hours); $i++) {

                                if ($working_hours[$i]['hour'] <= $totalHour) {
                                    foreach ($half_day_leave as $key => $halfLeave) {
                                        if ($halfLeave->user_id == $working_hours[$i][0]->id && $halfLeave->from == date("Y-m-d") && $halfLeave->type == '3' && $halfLeave->status == '1') {
                                            unset($working_hours[$i]);
                                            $working_hours = array_values($working_hours);

                                        }
                                    }
                                    $j++; ?>
                                    <div class="leave-wrap d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="new-cmn-btn mt-0 p-0 border-0">
                                                <?php if ($working_hours[$i][0]->image != '') { ?>
                                                    <img class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/' . $working_hours[$i][0]->image ?>"
                                                        alt="festival-img" />
                                                <?php } else { ?>
                                                    <img class="lazy" data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>"
                                                        alt="festival-img" />
                                                <?php } ?>
                                            </div>
                                            <div>
                                                <h5 class="semi-16"><?= $working_hours[$i][0]->username ?></h5>
                                                <p class="med-12 mt-2"><?= $working_hours[$i][0]->tech ?> </p>

                                            </div>

                                        </div>
                                        <div>
                                            <p class="in-time"><?= $working_hours[$i]['hour'] ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }

                            }


                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<h6 style="display: none; cursor:pointer" id="workingHour">Not completed working hours s(<?= $j ?>)</h6>
<!-- Upcoming Holidays Model -->

<div class="modal view-detail-modal view-detail-modal-small fade" id="upcomingHoliday">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Upcoming Holiday</h6>
                <div class="row view-detail-inner">
                    <div class="col-lg-12">

                    <div class="leave-wrap d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="new-cmn-btn mt-0 p-0 border-0">
                                <a href="https://cmexpertiseinfotech.com/new_erp/employee/profile/OTA">
                                    <img alt="" class="" data-src="https://cmexpertiseinfotech.com/new_erp/public/upload/user/image_1718080921.png" src="https://cmexpertiseinfotech.com/new_erp/public/upload/user/image_1718080921.png">
                                </a>
                            </div>
                            <div>
                                <a href="https://cmexpertiseinfotech.com/new_erp/employee/profile/OTA">
                                    <h5 class="semi-16">
                                        <p>
                                            Jayesh</p>

                                    </h5>
                                </a>
                                <p class="med-12 mt-2">PHP </p>
                            </div>
                        </div>
                    </div>

                        <?php if (!empty($upcoming_holiday)) {
                            for ($i = 0; $i < count($upcoming_holiday); $i++) {
                                ?>
                                <div class="leave-wrap d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <!--  <div class="new-cmn-btn mt-0 ">
                                    
                                </div> -->
                                        <div>
                                            <h5 class="semi-16"><?= $upcoming_holiday[$i][0]->day ?></h5>
                                            <p class="med-14"><?= date('l d M Y', strtotime($upcoming_holiday[$i][0]->date)) ?></p>
                                        </div>
                                    </div>

                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thought Modal -->

<div class="modal view-detail-modal fade" id="thought">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <!-- <h6 class="mb-0">Out of Deadline Project(s)</h6> -->
                <!-- <div class="row"> -->

                <div class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                    <img class="lazy" data-src="<?= base_url() . 'public/upload/thought/' . $getThought[0]->image ?>">
                </div>

                <!--  </div> -->
            </div>
        </div>
    </div>
</div>

<div class="modal view-detail-modal view-detail-modal-small fade" id="task_needed">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Task Needed</h6>
                <div class="row view-detail-inner">
                    <div class="col-lg-12">
                        <?php foreach ($task_needed as $key => $value): ?>
                            <div class="leave-wrap d-flex align-items-center justify-content-between">
                                <a
                                    href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value->user_id) ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="new-cmn-btn mt-0 p-0 border-0">
                                            <div>
                                                <?php if ($value->image != '') { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/' . $value->image ?>">
                                                <?php } else { ?>
                                                    <img alt="" class="lazy"
                                                        data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="semi-16"><?= $value->username ?></h5>

                                        </div>
                                    </div>
                                </a>

                            </div>
                        <?php endforeach ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal view-detail-modal view-detail-modal-small fade" id="finished_soon">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">
                <h6 class="mb-0">Task Finished Soon</h6>
                <div class="row view-detail-inner">
                    <div class="col-lg-12">
                        <?php
                        $estimate_time = 0;
                        $i = 0;
                        foreach ($finished_soon as $key => $value): ?>
                            <?php
                            $total_time = 0;
                            $total_time = ($value['estimate'] * 60) - ($value['duration_time'] * 60);
                            // echo $total_time."<br>";
                            if ($task_time > $total_time) {
                                $i++; ?>
                                <div class="leave-wrap d-flex align-items-center justify-content-between">
                                    <a
                                        href="<?= base_url() . 'employee/profile/' . $this->utility->safe_b64encode($value['user_id']) ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="new-cmn-btn mt-0 p-0 border-0">
                                                <div>
                                                    <?php if ($value['image'] != '') { ?>
                                                        <img alt="" class="lazy"
                                                            data-src="<?= base_url() . 'public/upload/user/' . $value['image'] ?>">
                                                    <?php } else { ?>
                                                        <img alt="" class="lazy"
                                                            data-src="<?= base_url() . 'public/upload/user/user.jpg' ?>">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="semi-16"><?= $value['username'] ?></h5>
                                            </div>
                                        </div>
                                    </a>
                                    <div>
                                        <?php if ($total_time < '0') { ?>
                                            <h5>Extra time : <?= -($total_time / 60) ?> min.</h5>

                                        <?php } else { ?>
                                            <h5>Remaining time : <?= date("H:i", $total_time) ?></h5>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php endforeach ?>
                        <h6 style="display: none;" id="task_finished_soon"><?= $i ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal view-detail-modal fade " id="birthday_post">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" id="close_bithday_modal">&times;</button>
            </div>
            <div class="new-cmn-box timesheet-wrap new-hr-dashboard going-project">


                <div class="leave-wrap d-flex align-items-center justify-content-between p-0 border-0">
                    <img class="lazy"
                        data-src="<?= base_url() . 'public/birthday_post/upload/' . $birthday_image[0]->birthday_card ?>"
                        id="photo" width="100%" height="100%">
                </div>
                <div align="center">
                    <input type="button" class="btn btn-primary save" value="<?= $this->label['Download'] ?>"
                        id="download">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('keyup', '.searchonGoingProjects', function () {
            var project_name = $(this).val();
            var base_url = $("#base_url").val();
            $.ajax({
                url: base_url + 'dashboard/searchonGoingProject',
                type: 'POST',
                data: { project_name: project_name },
                success: function (res) {
                    $('.ongoingSearch').html(res);
                }
            })
        })
    })
</script>