from typing import Dict, Optional, Tuple, Callable

MQTT_ERR_SUCCESS: int

class Client(object):
    def __init__(self, client_id: str, userdata: Dict) -> None: ...

    def username_pw_set(self, username: str, password: Optional[str]) -> None: ...

    def connect(self, host: str, port: int = 1883, keepalive: int = 60) -> int: ...

    def disconnect(self, reasoncode: Optional[int] = None) -> int: ...

    def is_connected(self) -> bool: ...

    def loop_start(self) -> Optional[int]: ...

    def loop_stop(self, force: bool = False) -> Optional[int]: ...

    def publish(self, topic: str, payload: Optional[str] = None, qos: int = 0, retain: bool = False) -> MQTTMessageInfo: ...

    def subscribe(self, topic: str, qos: int = 0) -> Tuple[int, int]: ...

    @property
    def on_connect(self) -> Callable: ...

    @on_connect.setter
    def on_connect(self, func: Callable) -> None: ...

    @property
    def on_disconnect(self) -> Callable: ...

    @on_disconnect.setter
    def on_disconnect(self, func: Callable) -> None: ...

    @property
    def on_message(self) -> Callable: ...

    @on_message.setter
    def on_message(self, func: Callable) -> None: ...

    @property
    def on_subscribe(self) -> Callable: ...

    @on_subscribe.setter
    def on_subscribe(self, func: Callable) -> None: ...

    @property
    def on_unsubscribe(self) -> Callable: ...

    @on_unsubscribe.setter
    def on_unsubscribe(self, func: Callable) -> None: ...

    @property
    def on_log(self) -> Callable: ...

    @on_log.setter
    def on_log(self, func: Callable) -> None: ...


class MQTTMessageInfo(object):
    @property
    def rc(self) -> int: ...


class MQTTMessage(object):
    @property
    def topic(self) -> str: ...

    @property
    def retain(self) -> bool: ...

    @property
    def payload(self) -> bytearray: ...