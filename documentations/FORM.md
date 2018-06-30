# A form builder

```php
   /**
    * @param int $type
    *
    * @return Form|null
    */
    form(int $type);
    
    $form =  form(Form::BOOTSTRAP || Form::FOUNDATION)
             ->start('action')
             ->method()
             ->method()
             ->end();
    
```


# Methods

| Name                  | Do                                    | Arguments                 | Return        |
|-----------------------|---------------------------------------|---------------------------|---------------|    
| create                | start form builder                    | string multiple           | Form          |
| startHide             | start hidden input                    | void                      | Form          |
| endHide               | close hidden input                    | void                      | Form          |
| file                  | add a file input                      | string multiples          | Form          |
| input                 | add an input                          | string multiples          | Form          |
| setType               | set form type                         | int  $type                | Form          |
| twoInlineInput        | add two inline input                  | string multiples          | Form          |
| csrf                  | add csrf token in form                | string $csrf              | Form          |
| button                | add a button                          | string multiples          | Form          |
| reset                 | add a reset button                    | string multiples          | Form          |
| textarea              | add a textarea                        | string multiples          | Form          |
| img                   | add an image                          | string multiples          | Form          |
| submit                | add a submit button                   | string multiples          | Form          |
| link                  | add a link button                     | string multiples          | Form          |
| select                | add a select input                    | string multiples          | Form          |
| twoInlineSelect       | add two inline select                 | string multiples          | Form          |
| checkbox              | add a checkbox                        | string multiples          | Form          |
| radio                 | add a radio                           | string multiples          | Form          |
| end                   | close and return form                 | void                      | string        |
| redirectSelect        | add a redirect select                 | string multiples          | Form          |
| twoRedirectSelect     | add two redirect select               | string multiples          | Form          |
| oneSelectAndOneInput  | add one select and one input          | string multiples          | Form          |
| oneInputAndOneSelect  | add one input and one select          | string multiples          | Form          |
  