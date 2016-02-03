JDocGen
=======

JSON Scheme to REST API visualization and documentation


Key features
====================
1. Support for [http://json-schema.org/](http://json-schema.org/)
2. View methods and objects rest-api in an easy-to-read web interface
3. Extensive descriptions of attributes, including the use of **html**
4. History of changes between builds documentation
5. Build management of the web interface with one button
6. Overall statistics on the methods and objects

Tree of methods and objects
---------------------------
![land](https://pp.vk.me/c629431/v629431610/33f53/x0SvbgHAF-M.jpg)

Example of method
---------------------------

![land](https://pp.vk.me/c629431/v629431610/33f5a/Xm261Lvo_ZM.jpg)

Example of object
---------------------------

![land](https://pp.vk.me/c629431/v629431610/33f61/s0zM8-yRZsk.jpg)

History
---------------------------

![land](https://pp.vk.me/c629431/v629431610/33f68/REIGEblKKHU.jpg)

Statistics methods
---------------------------

![land](https://pp.vk.me/c629431/v629431610/33f6f/vSMo2RmmSTA.jpg)

Statistics objects
---------------------------

![land](https://pp.vk.me/c629431/v629431610/33f76/L5QUI7sAaYw.jpg)

Setup
===================

1. Install the project by the rules **Yii2**
2. Install migration - **. /yii migrate**
3. Access by default - **admin/admin**

Operating procedure
===============

1. Set up for the current domain source json-file 
2. Edit the json-documentation files
3. Push the button "Update documentation", updated documentation for the selected version

Object description
================

1. Catalog placement - **objects**
2. File name - with a small letter, e.g. **app/json-schema/v8/objects/chatMessage.json**
3. Describe objects standard json schema
4. By default, set **additionalProperties** as **false** **("additionalProperties": false)**

### Advanced object description

#### PARENT - indication of parent object

```json
"$schema": "http://json-schema.org/draft-04/schema#",
"id": "chatMessageParamsPlace",
"title": "Options for the type of place",
"type": "object",
"meta": {
  "parent": "chatMessage"
}
```

### Advanced attribute description

#### HTML - description as the html

```json
"entity_id": {
  "meta": {
    "html": "<table class=\"table\"> ... </table>"
  },
  "description": "Description of the parameter depends on entity_type:",
  "type": "string"
}
```

#### ENUM - Description of the enum elements

```json
"type": {
  "meta": {
    "enum": {
      "event": "description for event",
      "combo": "description for combo"
    }
  },
  "description": "default - text",
  "enum": [
    "event",
    "combo"
  ]
}
```

#### RESTRICTIONS - description of the restrictions on the business logic

```json
"from_chat_id": {
  "meta": {
    "restrictions": "Must be specified if the parameter is specified from_time"
  },
  "type": "string"
}
```

### Method description

Allocation - **methods/{controller_id}/{action_id}.json**, e.g. **methods/auth/changePhone.json**

#### The file structure for an individual action

```json
{
  "title": "Action name",
  "description": "Action description",
  "request": {
    ...
  },
  "response": {
    ...
  }
}
```

#### The file for the controller description

The directory controller placed a required file **meta.json**, which provides the name of the controller.

```json
{
  "title": "Authorization"
}
```