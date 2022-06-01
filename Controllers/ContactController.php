<?php
namespace Controllers;

require __DIR__."../../App/Conection.php";
use App\Conection;

class ContactController extends Conection{

    function __construct()
    {
        $this->conection = $this->conection();
    }

    public function show(){
        try {
            $sql = "SELECT * FROM contacts";
            $query = mysqli_query($this->conection, $sql);
            $contacts = mysqli_fetch_all($query, MYSQLI_ASSOC);
            foreach($contacts AS $key => $contact){
                $sqlPhones = "SELECT * FROM phone_contacts WHERE contact_id = {$contact['id']}";
                $queryContacts = mysqli_query($this->conection, $sqlPhones);
                $phones = mysqli_fetch_all($queryContacts, MYSQLI_ASSOC);
                $contacts[$key]['phones'] = $phones;
            }
            return $contacts;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function create($name, $lastname, $email, $phones){
       
        try{
            $query = "INSERT INTO contacts (name,lastname,email) VALUES ('{$name}','{$lastname}', '{$email}')";
            $sql = mysqli_query($this->conection, $query);
            $id = mysqli_insert_id($this->conection);

            //Insert contact phone/s
            if($id > 0){
                foreach($phones AS $phone){
                    $query = "INSERT INTO phone_contacts (contact_id,phone) VALUES ('{$id}','{$phone}')";
                    $sql = mysqli_query($this->conection, $query);
                }
            }
            return  true;
        }
        catch (\Throwable $th) {
            //throw $th;
            die($th);
        }
    }
    public function update($id, $name, $lastname, $email){
        try {
            //Update contact
            $query = "UPDATE contacts SET name = '{$name}', lastname = '{$lastname}', email = '{$email}' WHERE id = $id";
            $result = mysqli_query($this->conection, $query);
            return true;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function updatePhone($id, $phone){
        try {
            //Update phone contact
            $query = "UPDATE phone_contacts SET phone = '{$phone}' WHERE id = $id";
            $result = mysqli_query($this->conection, $query);
            return true;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }
    public function destroy($id){
        try {
            //Delete contact
            $query = "DELETE FROM contacts WHERE id = $id";
            $result = mysqli_query($this->conection, $query);
            //Detele phones
            $query = "DELETE FROM phone_contacts WHERE contact_id = {$id}";
            $result = mysqli_query($this->conection, $query);
            return true;
        } catch (\Throwable $th) {
            return throw $th;
        }
    }

}