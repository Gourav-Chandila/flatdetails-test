<?php

class DatabaseException extends Exception
{
    public function errorMessage()
    {
        return 'There was an issue with the database: ' . $this->getMessage();
        // return 'There was an issue with the database: ';
    }
}

class ForeignKeyConstraintException extends Exception
{
    public function errorMessage()
    {
        return 'Foreign key constraint violation: ';
    }
}

class DuplicateEntryException extends Exception
{
    public function errorMessage()
    {
        return 'Duplicate entry: ' . $this->getMessage();
    }
}

class InvalidDataException extends Exception
{
    public function errorMessage()
    {
        return 'Invalid data: ' . $this->getMessage();
    }
}




?>