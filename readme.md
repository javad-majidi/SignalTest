# 📄 File Rotation API - README

## 🛠 Description
This is a simple PHP-based backend API that stores incoming request data in text files. It rotates filenames from `100.txt` down to `1.txt`, then starts over at `100.txt`.

---

## 🚀 How to Run the Server

```bash
php -S localhost:8005
```

> Runs the API using PHP's built-in server. The default endpoint becomes:
> `http://localhost:8005/index.php`

---

## 🧪 How to Run Tests

```bash
php test.php
```

> Runs a set of automated tests that ensure files are created and rotated correctly.

---

## 📬 How to Use the API (Send 100 Requests)

```bash
bar() {
  local total=$1
  local count=0

  for i in $(seq 1 $total); do
    curl -s -o /dev/null -X POST http://localhost:8005/index.php \
      -H "Content-Type: application/json" \
      -d "{\"request\": $i}"
    ((count++))
    percent=$((count * 100 / total))
    bar=$(printf '%*s' $((percent / 2)) '' | tr ' ' '#')
    printf "\r[%s] %d%% (%d/%d)" "$bar" "$percent" "$count" "$total"
  done
  printf "\nAll done!\n"
}

bar 100
```
 this is the bash named run100.sh and <font size="5">__run__</font> it via:
```bash
sh run100.sh
```
it also has some fancy bar like:

```bash 
[##################] 36% (36/100)
or:
[##################################################] 100% (100/100)
All done!
```

> This sends 100 POST requests to the API with JSON payloads, generating `100.txt` down to `1.txt` in the `data/` directory.

---

## 🧹 Clean Up

```bash
rm -rf ./data
```

> Deletes all the generated files and the `data/` directory.

---

## 📁 Project Structure

```
.
├── index.php        # The main API file
├── test.php         # Test suite for the API
└── data/            # Directory where request files are stored
```

---

## 📌 Requirements
- PHP 8.0 or higher
- `curl` (for shell testing)

---

## 📞 Contact
For questions or issues, feel free to open an issue or contact the maintainer.

# 📄 File Rotation API - README

## 🛠 Description
This is a simple PHP-based backend API that stores incoming request data in text files. It rotates filenames from `100.txt` down to `1.txt`, then starts over at `100.txt`.

---

## 🚀 How to Run the Server

```bash
php -S localhost:8005
```

> Runs the API using PHP's built-in server. The default endpoint becomes:
> `http://localhost:8005/index.php`

---

## 🧪 How to Run Tests

```bash
php test.php
```

> Runs a set of automated tests that ensure files are created and rotated correctly.

---

## 📬 How to Use the API (Send 100 Requests)

```bash
for i in {1..100}; do
  curl -s -X POST http://localhost:8005/index.php \
    -H "Content-Type: application/json" \
    -d "{\"request\": $i}"
done
```

> This sends 100 POST requests to the API with JSON payloads, generating `100.txt` down to `1.txt` in the `data/` directory.

---

## 🧹 Clean Up

```bash
rm -rf ./data
```

> Deletes all the generated files and the `data/` directory.

---

## 📁 Project Structure

```
.
├── index.php        # The main API file
├── test.php         # Test suite for the API
└── data/            # Directory where request files are stored
```

---

## 📌 Requirements
- PHP 8.0 or higher
- `curl` (for shell testing)

---

## 📞 Contact
For questions or issues, feel free to open an issue or contact the maintainer.

