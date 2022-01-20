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
final class Register
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

    /**
     * Validates the user input
     *
     * email is validated with a regex. You can use Utilities::isEmail() to do so.
     * Additionally email is checked for uniqueness against onlineshop.user.
     * password is validated with a regex. You can use Utilities::isPassword() to do so.
     *
     * Error messages are stored in the array $messages[].
     * Calls Register::business() if all input fields are valid.
     *
     * @return void Returns nothing
     */
    public function isValid(): void
    {
        if ((count($this->messages) === 0)) {
            $this->business();
        } else {
            $this->twigParams['email']= $_POST['email'];
            $this->twigParams['messages']= $this->messages;
            $this->twig->display("register.html.twig", $this->twigParams);
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
        $this->twig->display("index.html.twig");
    }

    /**
     * email of the POST-Array is checked for uniqueness against the table onlineshop.user.
     *
     * @return bool true, if email doesn't exist.
     *              false, if email exists.
     */
    private function isUniqueEmail(): bool
    {
            return true;
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
    private function addUser(): void
    {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $active = md5(uniqid(rand(), true));
        $query = /** @lang MySQL */
            <<<SQL
                 INSERT INTO user 
                 SET email = :email,
                     active = :active,
                     password = :password
SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            // PDO::PARAM_FLOAT/DECIMAL/DATE do not exist, PDO::PARAM_INT is only relevant for PK/FK
            // oder wenn die Datenbank keine Implizite Typkonvertierung entsprechend der Tabellendefinition vornimmt
            // You can omit bindValue, because the default type in $stmt->execute() is PDO::PARAM_STR
            // You definitely need bindValue for a LIMIT clause,  PDO::PARAM_INT is required for offset and rowcount.
            // --> executeStmt($params)
            // bindValue() is used instead of bindParam(),
            // because bindParam() is only needed for INPUT/OUTPUT parameters f.e. used in stored procedures
            // With bindParam() values can be overwritten between bind() and execute().
            // That is not, what we need in our use cases.
            $this->stmt->bindValue(':email' , $_POST['email'], PDO::PARAM_STR);
            $this->stmt->bindValue(':active' , $active, PDO::PARAM_STR);
            $this->stmt->bindValue(':password' , $password, PDO::PARAM_STR);
            $this->stmt->execute();
        }
    }
}
