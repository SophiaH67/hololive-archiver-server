from typing import List
from yaml import safe_load, YAMLError

with open("config.yaml", 'r') as stream:
  try:
    tmp_config = safe_load(stream)
  except YAMLError as exc:
    print("invalid config")
    raise exc

locations = {
    "final": tmp_config['locations']['final']
}


class holodex_search:
    org: str
    channel_id: str
    topics: List[str]

    def __init__(self, org: str, channel_id: str, topics: List[str]):
        self.org = org
        self.channel_id = channel_id
        self.topics = topics


# Parse a list of "{org}:{channel_id}:{topics}" into a list of holodex_search instances
holodex_searches: List[holodex_search] = []
for target in tmp_config["youtube_targets"]:
    target_parts = target.split(":")
    org = target_parts[0]
    channel_id = target_parts[1]
    topics = target_parts[2].split(",")

    if org == "*":
      org = None
    if channel_id == "*":
      channel_id = None
    if "*" in topics:
      topics = [None]

    holodex_searches.append(holodex_search(org, channel_id, topics))
