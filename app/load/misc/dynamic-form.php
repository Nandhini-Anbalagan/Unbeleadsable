<?php $dynamicFormId = Tokenizer::add('dynamic-form-id', 35, NULL, 0); ?>
<form id="<?php echo $dynamicFormId; ?>" onsubmit="return validate(this);" enctype="multipart/form-data"></form>