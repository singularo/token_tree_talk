
No longer do you need to write a form_alter hook in a .module file
like a caveman, simply implement this interface and then move the
form alter logic into the Bundle class of the entity.

Example:

In the bundle class, add the import:
```
use Drupal\bundle_form_alter\Form\BundleFormAlterInterface;
```

Then add the interface to the class definition:
```
class PageBundle extends Node Implements PageBundleInterface, BundleFormAlterInterface {
```

Then you can just add a formAlter in the Entity bundle class
```
  /**
   * {@inheritdoc}
   */
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void {
    $form['field_test']['#weight'] = 22;
  }
```
