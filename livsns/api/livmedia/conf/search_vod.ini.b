project.name = liv_mms_vod
project.default_charset = utf-8
server.index = 10.0.1.40:8383
server.search = 10.0.1.40:8384

[id]
type = id

[title]
type = title

[comment]
type = body

[channel_id]
index = self
tokenizer = full

[vod_sort_id]
index = self
tokenizer = full

[status]
index = self
tokenizer = full

[vod_leixing]
index = self
tokenizer = full

[is_allow]
index = self
tokenizer = full

[from_appid]
index = self
tokenizer = full

[from_appname]
index = both

[subtitle]
index = both

[keywords]
index = both

[author]
index = both

[duration]
index = self
tokenizer = full

[trans_use_time]
index = self
tokenizer = full

[mark_collect_id]
index = self
tokenizer = full

[create_time]
type = numeric

[playcount]
type = numeric

[click_count]
type = numeric

[downcount]
type = numeric

[bitrate]
type = numeric

[video_order_id]
type = numeric