<?php
namespace Exercises;

use Utilities\Utilities;
use PDO;
use Fhooe\Router\Router;

/**
 * The class Login implements the login to WebShop.
 *
 * On success the variable $_SESSION['loggedin'] is filled with a special hash.
 * User credentials are validated against the table onlineshop.user
 *
 * @author  Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class Login
{
    /**
     * @var array messages is used to display error and status messages after a form was sent an validated
     */
    private array $messages = [];

    /**
     * @var object twig provides a Twig object to display hmtl templates
     */
    private object $twig;

    /**
     * @var array twigParams is used to set variables passed to Twig
     */
    private array $twigParams = [];

    /**
     * Login constructor.
     *
     * Initializes Twig
     * Creates a database handler for the database connection.
     */
    public function __construct($twig)
    {
        $this->twig=$twig;
        $this->initDB();
    }

    /*
     * Initialize database connection
     *
     * @return void Returns nothing
     */
    private function initDB(): void
    {
        $charsetAttr="SET NAMES utf8 COLLATE utf8_general_ci";
        $dsn="mysql:host=db;port=3306;dbname=onlineshop";
        $mysqlUser="onlineshop";
        $mysqlPwd="geheim";
        $multi=false;
        $options = array(
            // A warning is given for persistent connections in case of a interrupted database connection.
            // This warning is shown on the web page if error_reporting=E_ALL is set in php.ini
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PDO::MYSQL_ATTR_INIT_COMMAND => $charsetAttr,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => $multi
        );
        $this->dbh = new PDO($dsn, $mysqlUser, $mysqlPwd, $options);
    }

    /**
     * Validates the user input
     *
     * email and password are required fields.
     * The combination of email + password is checked against database in @see Login::authenitcateUser()
     *
     * Error messages are stored in the array $messages[].
     * Calls Login::business() if all input fields are valid.
     *
     * @return void Returns nothing
     */
    public function isValid(): void
    {
        if ((count($this->messages) === 0)) {
            if (!$this->authenticateUser()) {
                $this->messages['nologin'] = "Invalid user name or password or account has not been activated yet.";
            } else {
                $this->business();
            }
        }
        $this->twigParams['email'] = $_POST['email'];
        $this->twigParams['messages'] = $this->messages;
        $this->twig->display("login.html.twig", $this->twigParams);
    }

    /**
     * Process the user input, sent with a POST request
     *
     * If a user called a page, that is protected by login, he will be redirected back to the page he requested.
     * If he directly requested the login page he is redirected to index.php
     * A page protected by login has to store its name in $_SESSION['redirect'] to make this redirect possible.
     *
     * @return void Returns nothing
     */
    protected function business(): void
    {
        isset($_SESSION['redirect']) ? $redirect = Router::urlFor($_SESSION['redirect']) : $redirect = Router::urlFor("/");
        Router::redirectTo($redirect);
    }

    /**
     * Validates email and password against onlineshop.user
     *
     * After a successful login the session_id is regenerated to make session hijacking more difficult.
     * session_regenerate_id() is used for that.
     * After that the corresponding session_ids in onlineshop.cart have to be replaced with the new one.
     *
     * In the table onlineshop.user the BCRYPT algorithm ist used for hashing onlineshop.user.password.
     * This was done in PHP 5.6 with password_hash(... , PASSWORD_DEFAULT)
     *
     * With PHP 7.3 the challenge is to update older hashes to the strongest hash, that is currently available.
     * Therefore password_get_info(), password_verify() and password_needs_rehash() are used to store
     * an argon2 hash in onlineshop.user.password, after a successful login against the old password hash.
     *
     * @return bool true, if email+password match a row in onlineshop.user, else false.
     */
    private function authenticateUser(): bool
    {
        //TODO use $old_session_id=1 for testing purpose as provided in onlineshop.cart
        //TODO when the whole shop works, you can switch to session_id()
        $query = <<<SQL
        SELECT iduser, first_name, last_name, password
        FROM user
        WHERE email = :email
        AND active IS NULL
SQL;
        $params = array(':email' => $_POST['email']);
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $this->stmt->execute($params);
            $rows = $this->stmt->fetchAll();
        }
        if (count($rows) === 1 && password_verify($_POST['password'], $rows[0]->password)) {
            $old_session_id = session_id();
            session_regenerate_id();
            $this->updateCart($old_session_id, session_id());
            $_SESSION['iduser']=$rows[0]->iduser;
            $_SESSION['isloggedin'] = Utilities::generateLoginHash();
            $_SESSION['first_name']=$rows[0]->first_name;
            $_SESSION['last_name']=$rows[0]->last_name;
            if (password_needs_rehash($rows[0]->password, PASSWORD_ARGON2I)) {
                $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
                $this->updateUser($password);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Replaces the session_ids in onlineshop.cart after the session has been regenerated after a successful login.
     *
     * @return void
     */
    private function updateCart($old_session_id, $new_session_id): void
    {
    }

    /**
     * Replaces the old password hash with ARGON2i in the table onlineshop.user
     *
     * @return void Returns nothing
     */
    private function updateUser($password): void
    {
    }
}
