<?php
   /**
   *@backupGlobals disabled
   *@backupStaticAttributes disabled
   */

   require_once "src/Task.php";
   require_once "src/Category.php";

   $DB = new PDO('pgsql:host=localhost;dbname=to_do_test');


   class TaskTest extends PHPUnit_Framework_TestCase

   {
       protected function tearDown()
       {
           Task::deleteAll();
       }



       function test_save()
       {
           //Arrange
           $name = "Home stuff";
           $id = null;
           $test_category = new Category($name, $id);
           $test_category->();


           $description = "Wash the dog";
           $category_id = $test_category->getId();
           $test_task = new Task($description, $id, $category_id);


           //Act
           $test_task->save();

           //Assert
           $result = Task::getAll();
           $this->assertEquals($test_task, $result[0]);


       }

       function test_getAll()
       {
           //Arrange
           $name = "Home stuff";
           $id = null;
           $test_category = new Category($name, $id);
           $test_category->save();

           $description = "Wash the dog";
           $category_id = $test_category->getId();
           $test_task = new Task($description, $id, $category_id);
           $test_task->save();


           $description2 = "Water the lawn";
           $test_task2 = new Task($description2, $id, $category_id);
           $test_task2->save();

           //Act
           $result = Task::getAll();

           //Assert
           $this->assertEquals([$test_task, $test_task2], $result);
       }

       function test_deleteAll()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $category_id = $test_category->getId();
            $test_task = new Task($description, $id, $category_id);
            $test_task->save();

            $description2 = "Water the lawn";
            $test_task2 = new Task($description2, $id, $category_id);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }


        function test_getId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = null;
            $test_Task = new Task ($description, $id);

            //Act
            $result = $test_Task->getId();

            //Assert
            $this->assertEquals(1,$result);

        }
        function test_setId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = null;
            $test_Task = new Task($description, $id);

            //Act
            $test_Task->setId(2);

            //Assert
            $result = $test_Task->getId();
            $this->assertEquals(2, $result);
        }


        function test_find()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $category_id = $test_category->getId();
            $test_task = new Task($description, $id, $category_id);
            $test_task->save();

            $description2 = "Water the lawn";
            $test_task2 = new Task($description2, $id, $category_id);
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }


   }




 ?>
