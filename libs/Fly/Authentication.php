<?php
/**
 * \Fly\Authentication
 * Authentication.php
 *
 * Authentication class
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

class Authentication extends \Phalcon\Mvc\User\Component
{
    /**
     * Checking user existing in system
     *
     * @param  string  $email
     * @param  string  $password
     * @param  boolean $cookie
     * @param  boolean $log
     * @return boolean
     */
    public function check($email, $password, $cookie = false, $log = false)
    {
        $me = new \stdClass();

        $myUser = \Model\User::findFirst([
            'email = :femail: AND status = :status:',
            'bind' => [
                'femail' => $email,
                'status' => \Model\User::STATUS_ENABLE
            ]
        ]);

        if ($myUser) {
            if ($this->security->checkHash($password, $myUser->password)) {
                $me->id = $myUser->id;
                $me->email = $myUser->email;
                $me->name = $myUser->name;
                $me->role = $myUser->role;
                $me->avatar = $myUser->avatar;

                // create session for user
                $this->session->set('me', $me);

                // store cookie if chosen
                if ($cookie == true) {
                    $this->cookie->set('remember-me', serialize($me), time() + 15 * 86400);
                }

                if ($log) {
                    // Store user logged in (LOG_IN::userId::userEmail::userAgent::ip)
                    $this->logger->name = 'access'; // Your own log name
                    $this->logger->info('LOG_IN::'
                            . $myUser->id .'::'
                            . $myUser->email .'::'
                            . $this->request->getUserAgent() .'::'
                            . $this->request->getClientAddress()
                        );
                }

                return true;
            } else {
                $this->flash->error('Thông tin tài khoản không đúng.');
            }
        } else {
            $this->flash->error('Thông tin tài khoản không đúng.');
        }
    }
}
