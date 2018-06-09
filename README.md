# Private property access benchmarks

This repo contains a set of benchmarks comparing different ways to access private properties, 
as discussed [here](https://www.lambda-out-loud.com/posts/accessing-private-properties-php/).

Sample output:

```
PHP version: PHP 7.2.4-1+b1
Host: Linux Whirlpool 4.14.0-3-amd64 #1 SMP Debian 4.14.17-1 (2018-02-14) x86_64
Iterations: 1000000

+------------|-------+
| Method     | Time  |
+------------|-------+
| Readers            |
+------------|-------+
| Getter     | 91ms  |
| Array cast | 213ms |
| Reflection | 404ms |
| Closures   | 423ms |
+------------|-------+
| Writers            |
+------------|-------+
| Setter     | 88ms  |
| Reflection | 404ms |
| Closures   | 430ms |
| Array cast | 965ms |
+------------|-------+
```

Usage:

```
$ bin/console benchmark 100000
```
