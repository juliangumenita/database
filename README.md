## Getting Started

This library uses one of the latest PHP version (7.2.4).

## Database Class

### Checking Connection

```
Database::connected();
```

### Creating A Secure Query

Returns a secure string for risky areas.

```
$injection = '" WHERE 1 = 1; "';
$safe = Database::secure($injection);
```

### Creating and Executing a Query

This method does nothing but executing the query straightforward, good for heavy usage.

```
$query = "DELETE FROM users WHERE banned = true";

Database::execute($query);
```

### Checking If Query is Successfull

Returns true if the query successfully executed.

```
$query = "INSERT INTO users(name) VALUES ('Name')";

Database::success($query);
```

### Counting

Returns the count(integer) of rows; if not successful, returns 0.

```
$query = "SELECT id FROM users";

Database::count($query);
```

### Fetching

Fetches only one row(array) from query.

```
$query = new "SELECT * FROM users WHERE id = 1";

Database::fetch($query);
```

### Multiple Row Fetching

Fetches multiple rows from query and puts them into an array(arrays).

```
$query = "SELECT * FROM users";

Database::multiple($query);
```
