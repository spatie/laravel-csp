# Upgrades

## 2.x to 3.x

### Changed: Policies have been replaced by presets.

Presets are similar to policies, but laravel-csp v3 allows multiple presets to be used simultaneously. 

The configuration keys `policy` and `report_only_policy` have been replaced by `presets` and `report_only_presets`.

```diff
 return [
-    'policy' => Spatie\Csp\Policies\Basic::class,
+    'presets' => [
+        Spatie\Csp\Presets\Basic::class,
+    ],
-    'report_only_policy' => '',
+    'report_only_presets' => [
+        //    
+    ],
```

Your custom policies should be refactored to custom presets. Differences are:

- Presets must implement the `Spatie\Csp\Preset` interface instead of extending the `Spatie\Csp\Policues\Policy` class
- The `configure` method has an updated signature
- `addDirective` has been renamed to `add`, `addNonceForDirective` has been renamed to `addNonce`

That said, you might not need a preset anymore. If you're just adding directives without additional logic, you can now directly register them in the configuration file under the `directives` and `report_only_directives` keys.

Here's an example diff for a policy to preset refactor:

```diff
 use Spatie\Csp\Directive;
 use Spatie\Csp\Keyword;
-use Spatie\Csp\Policies\Policy;
+use Spatie\Csp\Policy;
+use Spatie\Csp\Preset;

-class MyPolicy extends Policy
+class MyPolicy implements Preset
 {
-    public function configure()
+    public function configure(Policy $policy): void
     {
-        return $this
-            ->addDirective(Directive::SCRIPT, Keyword::SELF)
-            ->addNonceForDirective(Directive::SCRIPT);
+        $policy
+            ->add(Directive::SCRIPT, Keyword::SELF)
+            ->addNonce(Directive::SCRIPT);
     }
 }
```

There's no more support for `reportOnly` and `shouldBeApplied` in presets, as there are other ways to accommodate this functionality.
