debugbar-module
===============

```diff
diff --git a/includes/bootstrap.inc b/includes/bootstrap.inc
index 75a1a5d..1cc9d99 100644
--- a/includes/bootstrap.inc
+++ b/includes/bootstrap.inc
@@ -503,6 +503,7 @@ function timer_stop($name) {
 
   if (isset($timers[$name]['start'])) {
     $stop = microtime(TRUE);
+    $timers[$name]['measures'][] = array($name, $timers[$name]['start'], $stop);
     $diff = round(($stop - $timers[$name]['start']) * 1000, 2);
     if (isset($timers[$name]['time'])) {
       $timers[$name]['time'] += $diff;
```
