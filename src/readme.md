## 打印通知协议

```json
{
  "cmd": "notifyTaskResult",
  "requestID": "123458976",
  "status": "initial",
  "printer": "GP-L80180 Series",
  "taskId": "1700102236603_TASK_0"
}
```

```json
{
  "cmd": "print",
  "requestID": "123458976",
  "taskID": "1700102236603_TASK_0",
  "status": "success",
  "msg": "no error",
  "errorCode": 0
}
```

```json
{
  "cmd": "notifyDocResult",
  "requestID": "123458976",
  "status": "rendered",
  "printer": "GP-L80180 Series",
  "taskId": "1700102236603_TASK_0",
  "documentId": "0123456789",
  "code": 0,
  "detail": "success"
}
```

```json
{
  "cmd": "notifyDocResult",
  "requestID": "123458976",
  "status": "printed",
  "printer": "GP-L80180 Series",
  "taskId": "1700102236603_TASK_0",
  "code": 0,
  "detail": "success",
  "spendTime": {
    "total": 644,
    "downloading": 20,
    "pending": 17,
    "rendering": 607
  }
}
```

```json
{
  "cmd": "notifyPrintResult",
  "requestID": "123458976",
  "taskID": "1700102236603_TASK_0",
  "status": 0,
  "msg": "no error",
  "taskStatus": "rendered",
  "printer": "GP-L80180 Series",
  "evaluationSpendTime": 607,
  "pendingSpendTime": 17,
  "downloadingSpendTime": 20,
  "totalSpendTime": 644,
  "printStatus": [
    {
      "documentID": "0123456789",
      "detail": "",
      "msg": "no error",
      "printer": "GP-L80180 Series",
      "renderingSpendTime": 607,
      "renderingStartTime": "2023-11-16 10:37:16.881",
      "status": "success"
    }
  ]
}
```

```json
{
  "cmd": "notifyPrintResult",
  "requestID": "123458976",
  "taskID": "1700102236603_TASK_0",
  "status": 0,
  "msg": "no error",
  "taskStatus": "printed",
  "printer": "GP-L80180 Series",
  "evaluationSpendTime": 607,
  "pendingSpendTime": 17,
  "downloadingSpendTime": 20,
  "totalSpendTime": 644,
  "printStatus": [
    {
      "documentID": "0123456789",
      "detail": "",
      "msg": "no error",
      "printer": "GP-L80180 Series",
      "renderingSpendTime": 607,
      "renderingStartTime": "2023-11-16 10:37:16.881",
      "status": "success"
    }
  ]
}
```

```json
{
  "cmd": "notifyTaskResult",
  "requestID": "123458976",
  "status": "completeSuccess",
  "printer": "GP-L80180 Series",
  "taskId": "1700102236603_TASK_0",
  "spendTime": {
    "downloading": 20,
    "rendering": 607,
    "total": 644,
    "pending": 17
  },
  "docs": {
    "0123456789": {
      "cmd": "notifyDocResult",
      "requestID": "123458976",
      "status": "printed",
      "printer": "GP-L80180 Series",
      "taskId": "1700102236603_TASK_0",
      "code": 0,
      "detail": "success",
      "spendTime": {
        "total": 644,
        "downloading": 20,
        "pending": 17,
        "rendering": 607
      }
    }
  }
}
```
