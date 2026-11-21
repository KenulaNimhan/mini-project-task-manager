## Core Features to Implement
### 1. Project Management (Full CRUD)
- Create: A form to create a new project (Fields: name, description). 
- Read: A page to list all created projects. Each project should display the total number of
   tasks and the number of completed tasks (e.g., "3/5 Tasks Completed"). 
- Update: Ability to edit an existing project's name and description. 
- Delete (With Business Logic): Ability to delete a project. Important: A project cannot be
   deleted if it has any incomplete tasks. If a user attempts to delete such a project, the
   system must prevent the deletion and display a clear error message (e.g., "Cannot delete
   project: There are uncompleted tasks"). If all tasks are completed (or if there are no
   tasks), the project can be safely deleted along with its tasks.

### 2. Task Management (Livewire Full CRUD)
   When a project is clicked, it should display the tasks associated with that specific project. All
   actions here must be dynamic without page reloads. 
   
- Create: An input field to add a new task to the project.
- Read: Display the list of tasks for the selected project.
- Update:
  - Edit Details: Ability to edit the title of an existing task.
  - Toggle Status: Each task should have a checkbox. Checking/unchecking this box
     should instantly update the task's is_completed status in the database and visually
     cross out (strikethrough) the text.

- Delete: A button to delete a task dynamically from the list.
   
### Requirements & Best Practices 
- Migrations: Include migrations for projects and tasks tables 
- Validation: Ensure inputs are validated (e.g., project name is required, task title cannot
   be empty).

### Tech Stack Requirements
- Backend: Laravel (Latest version preferred)
- Frontend: Livewire, Tailwind CSS (for basic styling)
- Database: MySQL

