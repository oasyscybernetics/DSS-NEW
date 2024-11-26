<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Controller;
use RobThree\Auth\TwoFactorAuth;
use Xibo\Entity\User;
use Xibo\Exception\AccessDeniedException;
use Xibo\Exception\ConfigurationException;
use Xibo\Exception\InvalidArgumentException;
use Xibo\Exception\NotFoundException;
use Xibo\Exception\XiboException;
use Xibo\Factory\UserFactory;
use Xibo\Helper\Environment;
use Xibo\Helper\HttpsDetect;
use Xibo\Helper\LogoutTrait;
use Xibo\Helper\Random;
use Xibo\Helper\Session;
use Xibo\Service\ConfigServiceInterface;
use Xibo\Service\DateServiceInterface;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;
use Xibo\Storage\StorageServiceInterface;
use Xibo\Storage\PdoStorageService;


error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Class Login
 * @package Xibo\Controller
 */
class Login extends Base
{
    use LogoutTrait;
    /**
     * @var Session
     */
    private $session;

    /** @var StorageServiceInterface  */
    private $store;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /** @var \Stash\Interfaces\PoolInterface */
    private $pool;

    /**
     * Set common dependencies.
     * @param LogServiceInterface $log
     * @param SanitizerServiceInterface $sanitizerService
     * @param \Xibo\Helper\ApplicationState $state
     * @param User $user
     * @param \Xibo\Service\HelpServiceInterface $help
     * @param DateServiceInterface $date
     * @param ConfigServiceInterface $config
     * @param Session $session
     * @param UserFactory $userFactory
     * @param \Stash\Interfaces\PoolInterface $pool
     */
    public function __construct($log, $sanitizerService, $state, $user, $help, $date, $config, $session, $userFactory, $pool, $store)
    {
        $this->setCommonDependencies($log, $sanitizerService, $state, $user, $help, $date, $config);

        $this->session = $session;
        $this->userFactory = $userFactory;
        $this->pool = $pool;
        $this->store = $store;
    }

    /**
     * Output a login form
     * @throws \Xibo\Exception\ConfigurationException
     */
    public function loginForm()
    {

        // CAPTCHA
        include("../web/captcha/simple-php-captcha.php");
        $_SESSION['captcha'] = simple_php_captcha();
        $this->getApp()->flashNow('captcha_code', __($_SESSION['captcha']['code']));


        if(isset($_SESSION['loginCount'])){

           if($_SESSION['loginCount'] > 3)
           {
                $now = time();
                $ontime = $_SESSION['loginTime'] + 900;    //900 = 15 min X 60 sec

                if($now >= $ontime){
                    unset($_SESSION['loginCount']);
                    unset($_SESSION['loginTime']);
                }else{
                    $this->getLog()->error('Invalid login attempt.');
                    $this->getApp()->flashNow('login_message', __('Access denied for 30 minutes.' ));
                }
           }
        }


        // Check to see if the user has provided a special token
        $nonce = $this->getSanitizer()->getString('nonce');
       
        if ($nonce != '') {
            $sss = $nonce;

            //print_r($nonce);exit;

            // We have a nonce provided, so validate that in preference to showing the form.
            $nonce = explode('::', $nonce);
            $this->getLog()->debug('Nonce is ' . var_export($nonce, true));

            $sss = explode('::', $sss);
            $val = $this->checkResetToken($sss[1]);

            // Existing Code for Digital Signage

            // $cache = $this->pool->getItem('/nonce/' . $nonce[0]);

            // $validated = $cache->get();

            // if ($cache->isMiss()) {
            //     $this->getLog()->error('Expired nonce used.');
            //     $this->getApp()->flashNow('login_message', __('This link has expired.'));
            // } else if (!password_verify($nonce[1], $validated['hash'])) {
            //     $this->getLog()->error('Invalid nonce used.');
            //     $this->getApp()->flashNow('login_message', __('This link has expired.'));
            // } else {
            //     // We're valid.
            //     $this->pool->deleteItem('/nonce/' . $nonce[0]);

            //     try {
            //         $user = $this->userFactory->getById($validated['userId']);

            //         // Dooh user
            //         if ($user->userTypeId === 4) {
            //             $this->getLog()->error('Cannot log in as this User type');
            //             $this->getApp()->flashNow('login_message', __('Invalid User Type'));
            //         }

            //         // Log in this user
            //         $user->touch(true);

            //         $this->getLog()->info($user->userName . ' user logged in via token.');

            //         // Set the userId on the log object
            //         $this->getLog()->setUserId($user->userId);

            //         // Overwrite our stored user with this new object.
            //         $this->getApp()->user = $user;

            //         // Expire all sessions
            //         $session = $this->session;

            //         // this is a security measure in case the user is logged in somewhere else.
            //         // (not this one though, otherwise we will deadlock
            //         $session->expireAllSessionsForUser($user->userId);

            //         // Switch Session ID's
            //         $session->setIsExpired(0);
            //         $session->regenerateSessionId();
            //         $session->setUser($user->userId);

            //         // Audit Log
            //         $this->getLog()->audit('User', $user->userId, 'Login Granted via token', [
            //             'IPAddress' => $this->getApp()->request()->getIp(),
            //             'UserAgent' => $this->getApp()->request()->getUserAgent()
            //         ]);

            //         $this->getApp()->redirectTo('home');

            //         // We're done here
            //         return;

            //     } catch (NotFoundException $notFoundException) {
            //         $this->getLog()->error('Valid nonce for non-existing user');
            //         $this->getApp()->flashNow('login_message', __('This link has expired.'));
            //     }
            // }

            //if ($cache->isMiss()) {
            if ($val[0] == 0) {
                $this->getLog()->error('Expired nonce used.');
                $this->getApp()->flashNow('login_message', __('This link has expired.'));
            } else if ($val[0] == 1) {
                $this->getLog()->error('Invalid nonce used.');
                $this->getApp()->flashNow('login_message', __('This link has expired.'));
            } else if ($val[0] == 2) {
                // We're valid.
                //$this->pool->deleteItem('/nonce/' . $nonce[0]);

                $uid = $val[1];

                try {
                    $user = $this->userFactory->getById($uid);
                    $this->ipcrUpdate($uid);

                    // Dooh user
                    if ($user->userTypeId === 4) {
                        $this->getLog()->error('Cannot log in as this User type');
                        $this->getApp()->flashNow('login_message', __('Invalid User Type'));
                    }

                    // Log in this user
                    $user->touch(true);

                    $this->getLog()->info($user->userName . ' user logged in via token.');

                    // Set the userId on the log object
                    $this->getLog()->setUserId($user->userId);

                    // Overwrite our stored user with this new object.
                    $this->getApp()->user = $user;

                    // Expire all sessions
                    $session = $this->session;

                    // this is a security measure in case the user is logged in somewhere else.
                    // (not this one though, otherwise we will deadlock
                    $session->expireAllSessionsForUser($user->userId);

                    // Switch Session ID's
                    $session->setIsExpired(0);
                    $session->regenerateSessionId();
                    $session->setUser($user->userId);

                    // Audit Log
                    $this->getLog()->audit('User', $user->userId, 'Login Granted via token', [
                        'IPAddress' => $this->getApp()->request()->getIp(),
                        'UserAgent' => $this->getApp()->request()->getUserAgent()
                    ]);

                    $this->getApp()->redirectTo('home');

                    // We're done here
                    return;

                } catch (NotFoundException $notFoundException) {
                    $this->getLog()->error('Valid nonce for non-existing user');
                    $this->getApp()->flashNow('login_message', __('This link has expired.'));
                }
            }
        }

        // Check to see if the password reminder functionality is enabled.
        $passwordReminderEnabled = $this->getConfig()->getSetting('PASSWORD_REMINDER_ENABLED');
        $mailFrom = $this->getConfig()->getSetting('mail_from');
        $authCASEnabled = isset($this->app->configService->casSettings);

        // Template
        $this->getState()->template = 'login';
        $this->getState()->setData([
            'passwordReminderEnabled' => (($passwordReminderEnabled === 'On' || $passwordReminderEnabled === 'On except Admin') && $mailFrom != ''),
            'authCASEnabled' => $authCASEnabled,
            'version' => Environment::$WEBSITE_VERSION_NAME
        ]);
    }

    /**
     * Login
     * @throws \Xibo\Exception\ConfigurationException
     */
    public function login()
    {
        // Capture the prior route (if there is one)
        $user = null;
        $redirect = 'login';
        $priorRoute = ($this->getSanitizer()->getString('priorRoute'));

        // CAPTCHA CHECK
        if ($_POST['captcha'] != $_SESSION['captcha']['code']) {
            $this->getApp()->flash('login_message', __('Invalid Captcha. Please try again.'));
            $this->getApp()->redirectTo('login');
        }


        if (isset($_SESSION['loginCount'])){

           if ($_SESSION['loginCount'] > 3)
           {
                $now = time();
                $ontime = $_SESSION['loginTime'] + 900;    //900 = 15 min X 60 sec
                 
                if($now >= $ontime){
                     unset($_SESSION['loginCount']);
                     unset($_SESSION['loginTime']);
                }
                else{
                     $this->getApp()->flash('login_message', __('Access denied for 30 minutes.'));
                     $this->getApp()->redirectTo('login');
                }
           }
        }


        try {
            // Get our username and password
            $username = $this->getSanitizer()->getUserName('username');
            $password = $this->getSanitizer()->getPassword('password');

            $this->getLog()->debug('Login with username ' . $username);

            // Get our user
            try {
                /* @var User $user */
                $user = $this->userFactory->getByName($username);

                // DOOH user/Retired user
                if ($user->userTypeId === 4 || $user->retired === 1) {
                    throw new AccessDeniedException('Sorry this account does not exist or does not have permission to access the web portal.');
                }

                // Check password
                $user->checkPassword($password);

                // check if 2FA is enabled
                if ($user->twoFactorTypeId != 0) {
                    $_SESSION['tfaUsername'] = $user->userName;
                    $this->app->redirect('tfa');
                }

                // We are logged in, so complete the login flow
                $this->completeLoginFlow($user);

                if($user->userId != 0) {
                    $msg = $this->checkExpiry($user->userId);
                }

                unset($_SESSION['loginCount']);
                unset($_SESSION['loginTime']);
            }
            catch (NotFoundException $e) {
                throw new AccessDeniedException('User not found');
            }

            $redirect = ($priorRoute == '' || $priorRoute == '/' || stripos($priorRoute, $this->getApp()->urlFor('login'))) ? $this->getApp()->urlFor('home') : $priorRoute;
        }
        catch (\Xibo\Exception\AccessDeniedException $e) {
            $this->getLog()->warning($e->getMessage());
            $this->getApp()->flash('login_message', __('Username or Password incorrect'));

            // Modify our return message depending on whether we're a DOOH user or not
            // we do this because a DOOH user is not allowed to log into the web UI directly and is API only.
            
            

            if ($user === null || $user->userTypeId != 4) {
                $this->getApp()->flash('login_message', __('Username or Password incorrect'));
            } else {
                $this->getApp()->flash('login_message', __($e->getMessage()));
            }


            if (isset($_SESSION['loginCount'])){

               $_SESSION['loginCount']++;
               $this->getApp()->flash('login_message', __('Username or Password incorrect' . " Invalid attempt " . $_SESSION['loginCount']));
            
               if ($_SESSION['loginCount'] > 3)
               {
                   $this->getApp()->flash('login_message', __('Access denied for 30 minutes '));
                   $_SESSION['loginTime'] = time();
               }

            }else{

                $_SESSION['loginCount'] = 1;
                $_SESSION['loginTime'] = time();
                $this->getApp()->flash('login_message', __('Username or Password incorrect' . " Invalid attempt " . $_SESSION['loginCount']));
            }

            $this->getApp()->flash('priorRoute', $priorRoute);
            $this->getApp()->redirect($redirect);
        }
        catch (\Xibo\Exception\FormExpiredException $e) {
            $this->getApp()->flash('priorRoute', $priorRoute);
        }

        if($user->userId != 0) {
            $count = $this->checkSession($user->userId);            
            if($count >= 1) {
                $this->getApp()->flash('login_message', __('Multiple login of same account disallowed'));
                $this->logout();
            }
        }

        $this->setNoOutput(true);
        $this->getLog()->debug('Redirect to ' . $redirect);
        $this->getApp()->redirect($redirect);
    }

    /**
     * Forgotten password link requested
     * @throws \Xibo\Exception\XiboException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function forgottenPassword()
    {
        // Is this functionality enabled?
        $passwordReminderEnabled = $this->getConfig()->getSetting('PASSWORD_REMINDER_ENABLED');
        $mailFrom = $this->getConfig()->getSetting('mail_from');

        if (!(($passwordReminderEnabled === 'On' || $passwordReminderEnabled === 'On except Admin') && $mailFrom != '')) {
            throw new ConfigurationException(__('This feature has been disabled by your administrator'));
        }

        // Get our username
        $username = $this->getSanitizer()->getUserName('username');

        // Log
        $this->getLog()->info('Forgotten Password Request for ' . $username);

        // Check to see if the provided username is valid, and if so, record a nonce and send them a link
        try {
            // Get our user
            /* @var User $user */
            $user = $this->userFactory->getByName($username);

            // Does this user have an email address associated to their user record?
            if ($user->email == '') {
                throw new NotFoundException('No email');
            }

            // Nonce parts (nonce isn't ever stored, only the hash of it is stored, it only exists in the email)
            $action = 'user-reset-password-' . Random::generateString(10);
            $nonce = Random::generateString(20);

            $this->saveResetToken($user->userId, $nonce);

            // Create a nonce for this user and store it somewhere
            $cache = $this->pool->getItem('/nonce/' . $action);

            $cache->set([
                'action' => $action,
                'hash' => password_hash($nonce, PASSWORD_DEFAULT),
                'userId' => $user->userId
            ]);
            $cache->expiresAfter(1800); // 30 minutes?

            // Save cache
            $this->pool->save($cache);

            // Make a link
            $link = ((new HttpsDetect())->getUrl()) . $this->getApp()->urlFor('login') . '?nonce=' . $action . '::' . $nonce;

            // Uncomment this to get a debug message showing the link.
            //$this->getLog()->debug('Link is:' . $link);

            // Send the mail
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->From = $mailFrom;
            $msgFromName = $this->getConfig()->getSetting('mail_from_name');

            if ($msgFromName != null)
                $mail->FromName = $msgFromName;

            $mail->Subject = __('Password Reset');
            $mail->addAddress($user->email);

            // Body
            $mail->isHTML(true);
            $mail->Body = $this->generateEmailBody($mail->Subject, '<p>' . __('You are receiving this email because a password reminder was requested for your account. If you did not make this request, please report this email to your administrator immediately.') . '</p><a href="' . $link . '">' . __('Reset Password') . '</a>');

            if (!$mail->send()) {
                throw new ConfigurationException('Unable to send password reminder to ' . $user->email);
            } else {
                $this->getApp()->flash('login_message', __('Reminder email has been sent to your email address'));
            }

            // Audit Log
            $this->getLog()->audit('User', $user->userId, 'Password Reset Link Granted', [
                'IPAddress' => $this->getApp()->request()->getIp(),
                'UserAgent' => $this->getApp()->request()->getUserAgent()
            ]);

        } catch (XiboException $xiboException) {
            $this->getLog()->debug($xiboException->getMessage());
            $this->getApp()->flash('login_message', __('User not found'));
        }

        $this->setNoOutput(true);
        $this->getApp()->redirectTo('login');
    }

    /**
     * Log out
     * @param bool $redirect
     * @throws \Xibo\Exception\ConfigurationException
     */
    public function logout($redirect = true)
    {
        $this->completeLogoutFlow($this->getUser(), $this->session, $this->getLog(), $this->getApp());

        if ($redirect) {
            $this->getApp()->redirectTo('login');
        }
    }


    //  public function logout($redirect = true)
    // {
        
    //     $this->getUser()->touch();

    //     $uu_id = $_SESSION['userid'];

    //     // to log out a user we need only to clear out some session vars
    //     unset($_SESSION['userid']);
    //     unset($_SESSION['username']);
    //     unset($_SESSION['password']);

    //     $session = $this->session;
    //     $session->setIsExpired(1);
        
    //     if(isset($uu_id)!=''){
    //         $logService->audit('User', $uu_id, 'User logout', [
    //             'IPAddress' => $app->request()->getIp(),
    //             'UserAgent' => $app->request()->getUserAgent()
    //         ]);
    //     }

    //     if ($redirect)
    //         $this->getApp()->redirectTo('login');


    // }

    /**
     * Ping Pong
     */
    public function PingPong()
    {
        $this->session->refreshExpiry = ($this->getSanitizer()->getCheckbox('refreshSession') == 1);
        $this->getState()->success = true;
    }

    /**
     * Shows information about Xibo
     *
     * @SWG\Get(
     *  path="/about",
     *  operationId="about",
     *  tags={"misc"},
     *  summary="About",
     *  description="Information about this API, such as Version code, etc",
     *  @SWG\Response(
     *      response=200,
     *      description="successful response",
     *      @SWG\Schema(
     *          type="object",
     *          additionalProperties={
     *              "title"="version",
     *              "type"="string"
     *          }
     *      )
     *  )
     * )
     */
    function about()
    {
        $response = $this->getState();

        if ($this->getApp()->request()->isAjax()) {
            $response->template = 'about-text';
        }
        else {
            $response->template = 'about-page';
        }

        $response->setData(['version' => Environment::$WEBSITE_VERSION_NAME, 'sourceUrl' => $this->getConfig()->getThemeConfig('cms_source_url')]);
    }

    /**
     * Generate an email body
     * @param $subject
     * @param $body
     * @return string
     */
    private function generateEmailBody($subject, $body)
    {
        // Generate Body
        // Start an object buffer
        ob_start();

        // Render the template
        $this->app->render('email-template.twig', ['config' => $this->getConfig(), 'subject' => $subject, 'body' => $body]);

        $body = ob_get_contents();

        ob_end_clean();

        return $body;
    }

    /**
     * 2FA Auth required
     * @throws \Xibo\Exception\XiboException
     * @throws \RobThree\Auth\TwoFactorAuthException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function twoFactorAuthForm()
    {
        if (!isset($_SESSION['tfaUsername'])) {
            $this->getApp()->flash('login_message', __('Session has expired, please log in again'));
            $this->getApp()->redirectTo('login');
        }

        $user = $this->userFactory->getByName($_SESSION['tfaUsername']);

        // if our user has email two factor enabled, we need to send the email with code now
        if ($user->twoFactorTypeId === 1) {

            if ($user->email == '') {
                throw new NotFoundException('No email');
            }

            $mailFrom = $this->getConfig()->getSetting('mail_from');
            $issuerSettings = $this->getConfig()->getSetting('TWOFACTOR_ISSUER');
            $appName = $this->getConfig()->getThemeConfig('app_name');

            if ($issuerSettings !== '') {
                $issuer = $issuerSettings;
            } else {
                $issuer = $appName;
            }

            if ($mailFrom == '') {
                throw new InvalidArgumentException(__('Sending email address in CMS Settings is not configured'), 'mail_from');
            }

            $tfa = new TwoFactorAuth($issuer);

            // Nonce parts (nonce isn't ever stored, only the hash of it is stored, it only exists in the email)
            $action = 'user-tfa-email-auth' . Random::generateString(10);
            $nonce = Random::generateString(20);

            // Create a nonce for this user and store it somewhere
            $cache = $this->pool->getItem('/nonce/' . $action);

            $cache->set([
                'action' => $action,
                'hash' => password_hash($nonce, PASSWORD_DEFAULT),
                'userId' => $user->userId
            ]);
            $cache->expiresAfter(1800); // 30 minutes?

            // Save cache
            $this->pool->save($cache);

            // Make a link
            $code = $tfa->getCode($user->twoFactorSecret);

            // Send the mail
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->From = $mailFrom;
            $msgFromName = $this->getConfig()->getSetting('mail_from_name');

            if ($msgFromName != null) {
                $mail->FromName = $msgFromName;
            }

            $mail->Subject = __('Two Factor Authentication');
            $mail->addAddress($user->email);

            // Body
            $mail->isHTML(true);
            $mail->Body = $this->generateEmailBody($mail->Subject,
                '<p>' . __('You are receiving this email because two factor email authorisation is enabled in your CMS user account. If you did not make this request, please report this email to your administrator immediately.') . '</p>' . '<p>' . $code . '</p>');

            if (!$mail->send()) {
                throw new ConfigurationException('Unable to send two factor code to ' . $user->email);
            } else {
                $this->getApp()->flash('login_message',
                    __('Two factor code email has been sent to your email address'));
            }

            // Audit Log
            $this->getLog()->audit('User', $user->userId, 'Two Factor Code email sent', [
                'IPAddress' => $this->getApp()->request()->getIp(),
                'UserAgent' => $this->getApp()->request()->getUserAgent()
            ]);

        }

        // Template
        $this->getState()->template = 'tfa';
    }

    /**
     * @throws ConfigurationException
     * @throws NotFoundException
     * @throws \RobThree\Auth\TwoFactorAuthException
     */
    public function twoFactorAuthValidate()
    {
        $user = $this->userFactory->getByName($_SESSION['tfaUsername']);
        $result = false;
        $updatedCodes = [];

        if (isset($_POST['code'])) {
            $issuerSettings = $this->getConfig()->getSetting('TWOFACTOR_ISSUER');
            $appName = $this->getConfig()->getThemeConfig('app_name');

            if ($issuerSettings !== '') {
                $issuer = $issuerSettings;
            } else {
                $issuer = $appName;
            }

            $tfa = new TwoFactorAuth($issuer);

            if ($user->twoFactorTypeId === 1 && $user->email !== '') {
                $result = $tfa->verifyCode($user->twoFactorSecret, $this->getSanitizer()->getString('code'), 9);
            } else {
                $result = $tfa->verifyCode($user->twoFactorSecret, $this->getSanitizer()->getString('code'), 3);
            }
        } elseif (isset($_POST['recoveryCode'])) {
            // get the array of recovery codes, go through them and try to match provided code
            $codes = $user->twoFactorRecoveryCodes;

            foreach (json_decode($codes) as $code) {
                // if the provided recovery code matches one stored in the database, we want to log in the user
                if ($this->getSanitizer()->string($code) === $this->getSanitizer()->getString('recoveryCode')) {
                    $result = true;
                }

                if ($this->getSanitizer()->string($code) !== $this->getSanitizer()->getString('recoveryCode')) {
                    $updatedCodes[] = $this->getSanitizer()->string($code);
                }

            }
            // recovery codes are one time use, as such we want to update user recovery codes and remove the one that was just used.
            $user->updateRecoveryCodes(json_encode($updatedCodes));
        }

        if ($result) {
            // We are logged in at this point
            $this->completeLoginFlow($user);

            $this->setNoOutput(true);

            //unset the session tfaUsername
            unset($_SESSION['tfaUsername']);

            $this->getApp()->redirectTo('home');
        } else {
            $this->getLog()->error('Authentication code incorrect, redirecting to login page');
            $this->getApp()->flash('login_message', __('Authentication code incorrect'));
            $this->getApp()->redirectTo('login');
        }
    }

    /**
     * @param \Xibo\Entity\User $user
     * @throws \Xibo\Exception\ConfigurationException
     */
    private function completeLoginFlow($user)
    {
        $user->touch();

        $this->getLog()->info($user->userName . ' user logged in.');

        // Set the userId on the log object
        $this->getLog()->setUserId($user->userId);

        // Overwrite our stored user with this new object.
        $this->getApp()->user = $user;

        // Switch Session ID's
        $session = $this->session;
        $session->setIsExpired(0);
        $session->regenerateSessionId();
        $session->setUser($user->userId);

        // Audit Log
        $this->getLog()->audit('User', $user->userId, 'Login Granted', [
            'IPAddress' => $this->getApp()->request()->getIp(),
            'UserAgent' => $this->getApp()->request()->getUserAgent()
        ]);
    }



    // Disallow Multiple user logged In and Session Expiry

    public function checkSession($userID)
    {
        $dbh = PdoStorageService::newConnection();

        $sth = $dbh->prepare('
          SELECT * FROM `session` WHERE userId = :userID AND isExpired = 0
        ');

        $sth->execute(['userID' => $userID]);
            
        $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if(count($rows) > 0) {
            return count($rows);
        }
        else {
            return 0;
        }
    }

    public function checkExpiry($userID)
    {
        $msg = "";
        $dbh = PdoStorageService::newConnection();

        $sth = $dbh->prepare('
          SELECT * FROM `session` WHERE userId = :userID AND isExpired = 0
        ');

        $sth->execute(['userID' => $userID]);
        
        foreach ($sth->fetchAll(\PDO::FETCH_ASSOC) as $arr) {
            
            $sid = $arr['session_id'];
            //$sexp = $arr['session_expiration'];
            $lastAcc = $arr['lastAccessed'];
            $current = date("Y-m-d H:i:s");

            $to_time = strtotime($current);
            $from_time = strtotime($lastAcc);
            $diff = round(abs($to_time - $from_time) / 60);
            
            $msg = "\nSID: " . $sid . " \nLast Accessed: " . $lastAcc . "\nCurrent: " . $current . "\nDiff: " . $diff;

            if($diff >= 5) {
                // Change IsExpired to 1
                $sql = "UPDATE `session` SET isExpired = 1 WHERE userId = :userID AND isExpired = 0";
                $stmt = $dbh->prepare($sql);
                $stmt->execute(['userID' => $userID]);
            }
            
        }
        
        return $msg;
                
    }

    public function saveResetToken($userID, $token)
    {
        $dbh = PdoStorageService::newConnection();
        $sql = "UPDATE `user` SET resetToken = :resetToken, resetExpiry = :resetExpiry WHERE userId = :userID";
        
        $current = date("Y-m-d H:i:s");
        $expiry = date("Y-m-d H:i:s",strtotime($current." +5 minutes"));
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'resetToken' => $token,
            'resetExpiry' => $expiry,
            'userID' => $userID
        ]);
    }
    
    public function checkResetToken($token)
    {
        $dbh = PdoStorageService::newConnection();
        $sth = $dbh->prepare('
            SELECT * FROM `user` WHERE resetToken = :resetToken
          ');
        
        $sth->execute(['resetToken' => $token]);
        
        $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if(count($rows) == 0) {
            // TOKEN DOES NOT EXIST
            $arr = [];
            $arr[0] = 0;  $arr[1] = 0;  $arr[2] = $token;
            return $arr;
        }
        else if(count($rows) > 0) {
            
            foreach ($rows as $arr) {                   
                $u = $arr['userId'];
                $resetExpiry = $arr['resetExpiry'];
                $current = date("Y-m-d H:i:s");             
                
                $cur = strtotime($current);
                $rT = strtotime($resetExpiry);
                
                if ($cur >= $rT) {
                        // TOKEN IS EXPIRED
                    $a = [];
                        $a[0] = 1;  $a[1] = 0; $a[2] = $token;
                        return $a;
                }
                else {
                    $a = [];
                        $a[0] = 2;  $a[1] = $u; $a[2] = $token;
                        return $a;
                }
            }
            
        }
                    
    }
    
    
    public function ipcrUpdate($userID)
    {
            $dbh = PdoStorageService::newConnection();
            $sql = "UPDATE `user` SET isPasswordChangeRequired = 1 WHERE userId = :userID";
            $sth = $dbh->prepare($sql);
            $sth->execute(['userID' => $userID]);
    }
}
