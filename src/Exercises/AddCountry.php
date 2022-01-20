<?php
namespace Exercises;

use Utilities\Utilities;
use PDO;

/*
 * The class Register implements a registration of a user at WebShop.
 *
 * If user credentials are valid, they are stored in the table onlineshop.user.
 *
 * @author Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class AddCountry
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
     * Register Constructor.
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
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
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
     * name of country and ISOcode are validated with a regex. You can use Utilities::isString() to do so.
     *
     * Error messages are stored in the array $messages[].
     * Calls AddCountry::business() if all input fields are valid.
     *
     * @return void Returns nothing
     */
    public function isValid(): void
    {
        if (Utilities::isEmptyString($_POST['country'])) {
            $this->messages['country'] = "Please enter a country name.";
        }
        if (Utilities::isEmptyString($_POST['isocode'])) {
            $this->messages['isocode'] = "Please enter an isocode with exactly 2 letters for country above.";
        }
        if (!Utilities::isEmptyString($_POST['isocode']) && strlen($_POST['isocode']) !== 2) {
            $this->messages['isocode'] = "Please use exactly 2 letters for isocode.";
        }
        if ((count($this->messages) === 0)) {
            $this->business();
        } else {
            $this->twigParams['country'] = $_POST['country'];
            $this->twigParams['isocode'] = $_POST['isocode'];
            $this->twigParams['countries'] = $this->fillCountry();
            $this->twigParams['messages']= $this->messages;
            $this->twig->display("addcountry.html.twig", $this->twigParams);
        }
    }

    /**
     * Process the user input, sent with a POST request
     *
     * Writes the data with addUser() into table onlineshop.user.
     * On success the user is redirected to index.html.twig.
     *
     * @return void Returns nothing
     */
    protected function business(): void
    {
        $this->addCountry();
        $this->twigParams['countries'] = $this->fillCountry();
        $this->twig->display("addcountry.html.twig", $this->twigParams);
    }

    /**
     * Returns all entries of the table onlineshop.product_category in an array.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    public function fillCountry(): array
    {
        $result = [];
        $query = <<<SQL
                 SELECT name, ISOcode 
                 FROM country
        SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
        }
        return $result;
    }

    /**
     * Stores the data in the table onlineshop.user
     *
     * The field active stores a MD5-Hash to determine, that a two-phase authentication has not been finished yet.
     * If active is set to NULL, when clicking a link with this hash sent via email, the user can log in.
     *
     * @see Login.php
     * role has a default value (user) and can be left empty, if you allow only normal users to register via this form.
     * date_registered can be omitted it is filled with CURRENT_TIMESTAMP(), to store the current timestamp.
     * phone, mobile und fax are not required and can be null.
     * All other fields are directly stored to the table onlineshop.user.
     *
     * To test, if a login with login.php works with the current data,
     * set onlineshop.user.active to null with PHPMyAdmin
     *
     * @return void Returns nothing
     */
    private function addCountry(): void
    {
        $query = /** @lang MySQL */
            <<<SQL
                 INSERT INTO country 
                 SET name = :country,
                     ISOcode = :isocode
        SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $params = [":country" => $_POST['country'],":isocode" => $_POST['isocode']];
            $this->stmt->execute($params);
        }
    }
}
