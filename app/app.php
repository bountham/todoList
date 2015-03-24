

<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";
    $app = new Silex\Application();
    $app['debug'] = true;
    $DB = new PDO('pgsql:host=localhost;dbname=to_do');
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__."/../views"
    ));
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.twig', array('categories' => Category::getAll(), 'tasks' => Task::getAll()));
    });
    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });
    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });
    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $task = new Task($description);
        $task->save();
        return $app['twig']->render('tasks.twig', array( 'tasks' => Task::getAll()));
    });
    $app->get("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task.twig', array('task' =>$task, 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });
    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });
    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });
    $app->post("/add_tasks", function() use ($app) {
        $category = Category::find ($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $category->addTask($task);
        return $app['twig']->render('category.twig', array('category' => $category, 'categories' => Category::getAll(), 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });
    $app->post("/add_categories", function() use ($app) {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $task->addCategory($category);
        return $app['twig']->render('task.twig', array('task' => $task, 'tasks' => Task::getAll(), 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });
    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.twig');
    });
    $app->get("/tasks/{id}/edit", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('edit.twig', array('task' => $task));
    });
    $app->get("/categories/{id}/edit", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('edit_category.twig', array('category' => $category));
    });
    $app->patch("/edit_task", function() use ($app) {
        $new_description = $_POST['description'];
        $task_id = $_POST['task_id'];
        $task = Task::find($task_id);
        $task->update($new_description);
        return $app['twig']->render('tasks.twig', array('task_id' => $task_id, 'tasks' => Task::getAll()));
    });
    $app->patch("/edit_category", function() use ($app) {
        $new_name = $_POST['name'];
        $category_id = $_POST['category_id'];
        $category = Category::find($category_id);
        $category->update($new_name);
        return $app['twig']->render('categories.twig', array('category_id' => $category_id, 'categories' => Category::getAll()));
    });
    $app->delete("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });
    return $app;
?>
