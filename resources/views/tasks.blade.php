<!DOCTYPE html>
<html>
<head>
  <title>Laravel Three-Tier App</title>
  <style>
    body{font-family:Arial;max-width:600px;margin:40px auto}
    li{margin:8px 0} button{margin-left:10px}
  </style>
</head>
<body>
  <h2>Laravel Three-Tier App (GitHub + Jenkins + Kubernetes)</h2>
  <form method="POST" action="/tasks">
    @csrf
    <input name="title" placeholder="New task" required>
    <button type="submit">Add</button>
  </form>
  <ul>
    @foreach($tasks as $task)
      <li>{{ $task->title }}
        <form method="POST" action="/tasks/{{ $task->id }}" style="display:inline">
          @csrf @method('DELETE')
          <button type="submit">Delete</button>
        </form>
      </li>
    @endforeach
  </ul>
  <small>Served by pod: {{ gethostname() }}</small>
</body>
</html>
