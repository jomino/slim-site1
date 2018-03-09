<?php

namespace Core;

use \App\Controllers as Controllers;
use \App\Controllers\Test as Test;

class Routes
{
    public function __construct($app)
    {
        
        $app->get('/', Controllers\LoginController::class);

        $app->map(['POST','GET'],'/login', Controllers\LoginController::class)->setName('login');
        $app->map(['POST','GET'],'/logout', Controllers\LogoutController::class)->setName('logout');
        
        $app->group('/default', function () {
            /* MAIN */
            $this->get('/main', Controllers\MainDefaultController::class);
            $this->post('/body/home', Controllers\BodyDefaultHomeController::class)->setName('body_home');
            $this->post('/aside/home', Controllers\AsideDefaultController::class)->setName('aside_home');
            $this->post('/header/home[/{id}]', Controllers\HeaderDefaultController::class)->setName('header_home');
            /* USERS */
            $this->post('/body/contacts', Controllers\BodyDefaultUsersController::class.":home")->setName('body_contacts');
            $this->post('/contacts/pipe', Controllers\BodyDefaultUsersController::class.":pipe")->setName('contact_pipe');
            $this->post('/contacts/edit[/{id}]', Controllers\BodyDefaultUsersController::class.":edit")->setName('contact_edit');
            $this->post('/contacts/save[/{id}]', Controllers\BodyDefaultUsersController::class.":save")->setName('contact_save');
            /* PROPERTIES */
            $this->post('/body/properties', Controllers\BodyDefaultPropertiesController::class.":home")->setName('body_properties');
            $this->post('/properties/pipe', Controllers\BodyDefaultPropertiesController::class.":pipe")->setName('properties_pipe');
            $this->post('/properties/edit[/{id}]', Controllers\BodyDefaultPropertiesController::class.":edit")->setName('properties_edit');
            $this->post('/properties/save[/{id}]', Controllers\BodyDefaultPropertiesController::class.":save")->setName('properties_save');
            /* GESLOC */
            $this->post('/body/gesloc', Controllers\BodyDefaultGeslocController::class.":home")->setName('body_gesloc');
            $this->post('/gesloc/pipe', Controllers\BodyDefaultGeslocController::class.":pipe")->setName('gesloc_pipe');
            $this->post('/gesloc/edit[/{id}]', Controllers\BodyDefaultGeslocController::class.":edit")->setName('gesloc_edit');
            $this->post('/gesloc/save[/{id}]', Controllers\BodyDefaultGeslocController::class.":save")->setName('gesloc_save');
            /* GESLOPAY */
            $this->post('/geslocpay/view[/{id}]', Controllers\BodyDefaultGeslocpayController::class.":home")->setName('geslocpay_view');
            $this->post('/geslocpay/pipe[/{id}]', Controllers\BodyDefaultGeslocpayController::class.":pipe")->setName('geslocpay_pipe');
            $this->post('/geslocpay/edit[/{id}]', Controllers\BodyDefaultGeslocpayController::class.":edit")->setName('geslocpay_edit');
            $this->post('/geslocpay/save[/{id}]', Controllers\BodyDefaultGeslocpayController::class.":save")->setName('geslocpay_save');
            $this->post('/geslocpay/delete[/{id}]', Controllers\BodyDefaultGeslocpayController::class.":del")->setName('geslocpay_del');
            /* CALENDAR */
            $this->post('/calendar/view', Controllers\BodyDefaultCalendarController::class.":home")->setName('calendar_view');
            $this->post('/calendar/pipe[/{id}]', Controllers\BodyDefaultCalendarController::class.":pipe")->setName('calendar_pipe');
            /* MESSAGES */
            $this->post('/mailbox/view', Controllers\BodyDefaultMsgController::class.":home")->setName('mailbox_view');
            /* OPTIONS */
            $this->post('/options/view', Controllers\BodyDefaultOptionsController::class.":home")->setName('options_view');
        });

        $app->group('/admin', function () {
            $this->map(['POST','GET'],'', Controllers\AdminController::class)->setName('admin');
            $this->post('/gesloc/confirm[/{op}]', Controllers\GeslocConfirmDefaultController::class);
            $this->post('/gesloc/import', Controllers\GeslocImportDefaultController::class)->setName('gesloc_import');
            $this->post('/gesloc/delete', Controllers\GeslocDeleteDefaultController::class)->setName('gesloc_delete');
            $this->post('/gesloc/update', Controllers\GeslocUpdateDefaultController::class)->setName('gesloc_update');
            $this->post('/geslocpay/confirm[/{op}]', Controllers\GeslocpayConfirmDefaultController::class);
            $this->post('/geslocpay/import', Controllers\GeslocpayImportDefaultController::class)->setName('geslocpay_import');
            $this->post('/geslocpay/update', Controllers\GeslocpayUpdateDefaultController::class)->setName('geslocpay_update');
            $this->post('/geslochisto/confirm[/{op}]', Controllers\GeslochistoConfirmDefaultController::class);
            $this->post('/geslochisto/import', Controllers\GeslochistoImportDefaultController::class)->setName('geslochisto_import');
            $this->post('/geslocdoc/confirm[/{op}]', Controllers\GeslocdocConfirmDefaultController::class);
            $this->post('/geslocdoc/import', Controllers\GeslocdocImportDefaultController::class)->setName('geslocdoc_import');
            $this->post('/properties/confirm[/{op}]', Controllers\PropertiesConfirmDefaultController::class);
            $this->post('/properties/import', Controllers\PropertiesImportDefaultController::class)->setName('properties_import');
            $this->post('/properties/update', Controllers\PropertiesUpdateDefaultController::class)->setName('properties_update');
            $this->post('/properties/delete', Controllers\PropertiesDeleteDefaultController::class)->setName('properties_delete');
            $this->post('/users/confirm[/{op}]', Controllers\UsersConfirmDefaultController::class);
            $this->post('/users/import', Controllers\UsersImportDefaultController::class)->setName('users_import');
            $this->post('/users/update[/{id}]', Controllers\UsersUpdateDefaultController::class)->setName('users_update');
            $this->post('/users/delete[/{id}]', Controllers\UsersDeleteDefaultController::class)->setName('users_delete');
        });

        // test
        $app->group('/test', function () {
            $this->get('/admin', Test\AdminDefaultController::class);
            $this->get('/model', Test\ModelTestController::class);
            $this->get('/import', Test\ConnectorJsonDefaultController::class);
            $this->get('/update[/{id}]', Test\UsersUpdateDefaultController::class);
            $this->get('/delete[/{id}]', Test\UsersDeleteDefaultController::class);
            $this->post('/body/home', Test\BodyGetFootableController::class.":home")->setName('body_test');
            $this->post('/contact/pipe', Test\BodyGetFootableController::class.":pipe")->setName('test_contact_pipe');
        });
        
    }
}
