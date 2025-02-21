# Upgrades

## 2.x to 3.x

The configuration has been updated to support multiple policies. and the `Basic` policy has been renamed to `BasicPolicy`.

```diff
 return [
 
     /*
      * A policy will determine which CSP headers will be set. A valid CSP policy is
      * any class that extends `Spatie\Csp\Policies\Policy`
      */
-    'policy' => Spatie\Csp\Policies\Basic::class,
+    'policies' => [
+        Spatie\Csp\Policies\BasicPolicy::class,
+    ],
 
     /*
      * This policy which will be put in report only mode. This is great for testing out
      * a new policy or changes to existing csp policy without breaking anything.
      */
-    'report_only_policy' => '',
+    'report_only_policies' => [
+        //    
+    ],
```

Custom policies have updated return type. Add a `void` return type to methods you might have overridden.

```diff
-public function configure()
+public function configure(): void
```
