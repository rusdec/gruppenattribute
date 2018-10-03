# gruppenattribute
Группы свойств для разделов инфоблоков в Битрикс

## Какую задачу решает
### Дано:

**Инфоблок "Товары":**
  - **Раздел: Пылесосы**
    - Элемент 1: Циклон 3000
    - Элемент 2: Самсунг 8800Т
    - ...
  - **Раздел: Удленители**
    - Элемент 1: Удленитель УМК-40
    ...
    
**Свойства для раздела "Пылесосы":**
- Мощность
- Длина провода

**Свойства для раздела "Удленители":**
- Длина провода

При этом свойство "Длина провода" одно и является общим для двух разделов.

### Задача:
В списке товаров в кратком описании товара для товаров из раздела
- "Пылесосы" выводить только "Мощность"
- "Удленители" выводить только "Длина провода"

В детальной странице товара для товаров из раздела
- "Пылесосы" первым должно идти свойство "Мощность", вторым идти "Длина провода"
- "Удленители" первым должно идти свойство "Длина провода"

### Проблемы
1. На текущий момент (версия Битрикс 17.х) Битрикс хоть и позволяет задать сортировку свойств, но значение будет одно для одного свойства. Таким образом для разных групп товаров нельзя задать различную сортировку одних и тех же свойств.
2. При задании только одного значения сортировки, нельзя задать различную сортировку одного и того же свойства на разных страницах.

### Решение
Использовать Gruppenattribute.
Для текущей задачи решение будет выглядеть таким:

1. Создать группу "Список товаров"
   1. В созданной группе создать подгруппы "Пылесосы" и "Удленители"
   2. В каждую из подгрупп добавить нужные свойства и задать нужную сортировку

2. Создать группу "Детальная страница"
   1. В созданной группе создать подгруппы "Пылесосы" и "Удленители"
   2. В каждую из подгрупп добавить нужные свойства и задать нужную сортировку

3. В шаблонах нужных страниц использовать gruppenattribute для формирования массива с нужными свойствами. См. метод `Volex\GruppenAttribute\Utilities\groupProperties()` в `lib/entities/utilities.class.php`


## Где используется:
- [Бензолюкс](http://benzolux-shop.ru)
