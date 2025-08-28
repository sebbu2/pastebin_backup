# Replace messages comming from a Gateway (with discord, irc, jabber/xmpp, matrix, telegram or other) with native messages
# Author: sebbu
# License: CC0 https://creativecommons.org/publicdomain/zero/1.0/

import hexchat
import os
import types
from types import SimpleNamespace
import simplejson as json

__module_name__ = "gateway_support"
__module_version__ = "1.0.7"
__module_description__ = "Translate gateway'd message into native messages"

print ("Loading gateway_support")

chmsg = "Channel Message"
chmsg2= "Channel Msg Hilight"
chact = "Channel Action"
chact2= "Channel Action Hilight"
chnot = "Channel Notice"
chon = "Join"
choff = "Part"
choff2 = "Part with Reason"
off = "Quit"
nic = "Change Nick"
pract = "Private Action to Dialog"
prmsg = "Private Message to Dialog"
# join : nick, channel, [host, account]
# part : nick, host, channel, reason [ with reason]
# quit : nick, reason, host
# message : nick, text, modechar
# notice : who, channel, message
# nick : old, new

styles = {
    "\x02", # bold (STX)
    "\x03", # color (ETX)
    "\x04", # ?hex color? (EOT)
    "\x0F", # normal or end-of-style (SI)
    "\x11", # ?monospace? (DC1)
    "\x16", # reverse (SYN)
    "\x1D", # italic (GS)
    "\x1E", # ?strike-through? (RS)
    "\x1F", # underline (US)
}

dir=hexchat.get_info("xchatdir")+"\\gateway_support"
if not os.path.exists(dir):
    os.mkdir(dir)

chans = set()
chans2 = set()

def un_gateway_ize(words, word_eol, userdata):
    global chans, chans2
    # tripplet server, channel, gateway username
    serv = hexchat.get_info("network").lower()
    chan = hexchat.get_info("channel").lower()
    who = hexchat.strip(words[0])
    # basic conditions, real channel (notice), text
    #print(":".join("{:02x}".format(ord(c)) for c in who)) # debug invisible & unicode characters
    if len(words)<2:
        return hexchat.EAT_NONE
    if userdata["msgtype"]==chmsg or userdata["msgtype"]==chmsg2:
        if len(words)>=2:
            txt = words[1]
        else:
            txt = ""
    elif userdata["msgtype"]==chact or userdata["msgtype"]==chact2:
        txt= words[1]
    elif userdata["msgtype"]==chnot:
        txt = words[2]
        chan = words[1]
    who=who.replace('\u200b','').replace('\u200c','').replace('\u200d','')
    # user info
    user = [i for i in hexchat.get_list("users") if i.nick==who]
    if not user:
        user = SimpleNamespace(nick= who, account= 'gw', host= 'gw@gateway', realname= 'gateway')
    else:
        user = user[0]
    who = who.lower()
    if len(who)==0 or len(chan)==0 or len(txt)==0:
        return hexchat.EAT_NONE
    # single gateway
    if (serv, chan, who) in chans:
        who_ = txt.split(' ')
        who2a = who_[0]
        if len(who2a)==0:
            return hexchat.EAT_NONE
        who2 = hexchat.strip(who2a)
        # patterns
        if ((who2a[0] in styles and who2a[-1] in styles and who2a[1]!='<' and who2a[-2]=='>') or
          (who2a[0] in styles and who2a[-4] in styles and who2a[-3]=='@')):
            if userdata["msgtype"]==chmsg or userdata["msgtype"]==chmsg2:
                who2 = who2[0:-4]
                txt2 = ' '.join( who_[1:] )
            elif userdata["msgtype"]==chact or userdata["msgtype"]==chact2:
                who2 = who2[0:-4]
                txt2 = ' '.join( who_[1:] )
            elif userdata["msgtype"]==chnot:
                who2 = who2[0:-3]
                txt2 = ' '.join( who_[1:] )
            else:
                return hexchat.EAT_NONE
        elif who_[0]=='[' and who_[2]==']' and who_[3]==':':
            if len(who_)<4:
                return hexchat.EAT_NONE
            who2 = hexchat.strip(who_[1])
            txt2 = ' '.join( who_[4:] )
        elif (
            (who2[0]=='<' and who2[-1]=='>') or
            (who2[0]=='[' and who2[-1]==']') or
            (who2[0]=='`' and who2[-1]=='`') or
            (who2[0]=='x' and who2[-1]=='»') or
            (who2[0]=='@' and who2[-1]==':')
          ) :
            who2 = who2[1:-1]
            txt2 = ' '.join( who_[1:] )
        # control patterns
        elif (who.startswith('system/') or who=='system') and (who_[1].strip() in ['joins', 'leaves', 'parts']):
            txt2 = ''
        else:
            return hexchat.EAT_NONE
        user2 = [i for i in hexchat.get_list("users") if i.nick==who2]
        if not user2:
            hexchat.command('RECV :' + who2 + '!' + user.host + ' JOIN ' + chan)
        if (userdata["msgtype"]==chmsg) or (userdata["msgtype"]==chmsg2):
            if who.startswith('system/') or who=='system':
                if who_[1].strip()=='joins':
                    #hexchat.emit_print( chon, who2, chan, user.host)
                    hexchat.command('RECV :' + who2 + '!' + user.host + ' JOIN ' + chan)
                    return hexchat.EAT_ALL
                elif who_[1].strip() in ['leaves', 'parts']:
                    #hexchat.emit_print( choff, who2, user.host, chan)
                    hexchat.command('RECV :' + who2 + '!' + user.host + ' PART ' + chan)
                    return hexchat.EAT_ALL
                else:
                    return hexchat.EAT_NONE
            else:
                #hexchat.emit_print( userdata["msgtype"], who2, txt2)
                hexchat.command('RECV :' + who2 + '!' + user.host + ' PRIVMSG ' + chan + ' :' + txt2);
            return hexchat.EAT_ALL
        elif (userdata["msgtype"]==chact) or (userdata["msgtype"]==chact2):
            #hexchat.emit_print( userdata["msgtype"], who2, txt2)
            hexchat.command('RECV :' + who2 + '!' + user.host + ' PRIVMSG ' + chan + " :\1ACTION" + txt2 + "\1");
            return hexchat.EAT_ALL
        elif userdata["msgtype"]==chnot:
            # notice is usually control messages (join, let, quit, nick change)
            if txt2=="joined the channel":
                #hexchat.emit_print( chon, who2, chan, user.host)
                hexchat.command('RECV :' + who2 + '!' + user.host + ' JOIN ' + chan)
                return hexchat.EAT_ALL
            elif txt2=="left the channel":
                #hexchat.emit_print( choff, who2, user.host, chan)
                hexchat.command('RECV :' + who2 + '!' + user.host + ' PART ' + chan)
                return hexchat.EAT_ALL
            elif txt2=="quit the server":
                #hexchat.emit_print( off, who2, "", user.host)
                hexchat.command('RECV :' + who2 + '!' + user.host + ' QUIT')
                return hexchat.EAT_ALL
            elif ' '.join(txt2.split(' ')[:-1])=="is the new nick of":
                who3 = txt2.split(' ')[-1]
                #hexchat.emit_print( nic, who3, who2)
                hexchat.command('RECV :' + who2 + '!' + user.host + ' NICK ' + who3)
                return hexchat.EAT_ALL
            # debug, shouldn't happen
            print(who2)
            print(txt2)
        else:
            # debug, shouldn't happen
            print(userdata["msgtype"])
        return hexchat.EAT_NONE
    # multiple gateway
    if (serv, chan, who) in chans2:
        who_ = txt.split(' ')
        if len(who_)<2:
            return hexchat.EAT_NONE
        who2a = who_[0]
        if len(who2a)==0:
            return hexchat.EAT_NONE
        who2 = hexchat.strip(who2a)
        arg0 = hexchat.strip(who_[0])
        # allowed prefixes
        if(arg0!='[TG]' and arg0!='[telegram]' and arg0!='[FGD]' and arg0!='[xmpp]' and arg0!='[matrix]' and arg0!='[discord]'):
            return hexchat.EAT_NONE
        arg1 = hexchat.strip(who_[1])
        if len(who_)>=3:
            arg2 = hexchat.strip(who_[2])
        else:
            arg2 = ''
        act=False
        if(arg1[0]=='<' and arg1[-1]=='>'):
            who2 = arg1[1:-1]
            txt2 = ' '.join( who_[2:] )
        elif(arg1[0]=='x' and arg1[-1]=='»'):
            who2 = arg1[1:-1]
            txt2 = ' '.join( who_[2:] )
        elif(arg1[0]=='@' and arg1[-1]==':'):
            who2 = arg2
            txt2 = arg1 + ' ' + ' '.join( who_[3:] )
            act = True
        elif(who2[0] in styles and who2[-1] in styles and who2[-2]=='>'):
            who2 = arg1[1:-2]
            txt2 = ' '.join( who_[2:])
        elif(
            (arg1[0]>='a' and arg1[0]<='z') or
            (arg1[0]>='A' and arg1[0]<='Z')
        ):
            who2=arg1
            txt2 = ' '.join( who_[2:] )
            act = True
        else:
            return hexchat.EAT_NONE
        if act:
            if(userdata["msgtype"]==chmsg):
                what = chact
            elif(userdata["msgtype"]==chmsg2):
                what = chact2
            else:
                return hexchat.EAT_NONE
            #hexchat.emit_print( what, who2, txt2)
            hexchat.command('RECV :' + who2 + '!' + user.host + ' PRIVMSG ' + chan + ' :' + txt2);
            return hexchat.EAT_ALL
        else:
            hexchat.emit_print( userdata["msgtype"], who2, txt2)
            return hexchat.EAT_ALL
    return hexchat.EAT_NONE

def gateway_on(word, word_eol, userdata):
    global chans
    serv = hexchat.get_info("network").lower()
    chan = hexchat.get_info("channel").lower()
    who = hexchat.strip(word[1]).lower()
    if (serv, chan, who) not in chans:
        chans.add( (serv, chan, word[1].lower()) )
        # chans.append( (serv, chan, word[1].lower()) )
        print("added")
    else:
        print("already existed, so not added")
    # print("INFO '%s' '%s' '%s'" % (serv, chan, who) )
    return hexchat.EAT_ALL

def gateway_off(word, word_eol, userdata):
    global chans
    serv = hexchat.get_info("network").lower()
    chan = hexchat.get_info("channel").lower()
    who = hexchat.strip(word[1]).lower()
    if (serv, chan, who) in chans:
        chans.remove( (serv, chan, word[1].lower()) )
        print("removed")
    else:
        print("didn't existed, so not removed")
    # print("INFO '%s' '%s' '%s'" % (serv, chan, who) )
    return hexchat.EAT_ALL

def gateway_on2(word, word_eol, userdata):
    global chans2
    serv = hexchat.get_info("network").lower()
    chan = hexchat.get_info("channel").lower()
    who = hexchat.strip(word[1]).lower()
    if (serv, chan, who) not in chans2:
        chans2.add( (serv, chan, word[1].lower()) )
        # chans2.append( (serv, chan, word[1].lower()) )
        print("added")
    else:
        print("already existed, so not added")
    # print("INFO '%s' '%s' '%s'" % (serv, chan, who) )
    return hexchat.EAT_ALL

def gateway_off2(word, word_eol, userdata):
    global chans2
    serv = hexchat.get_info("network").lower()
    chan = hexchat.get_info("channel").lower()
    who = hexchat.strip(word[1]).lower()
    if (serv, chan, who) in chans2:
        chans2.remove( (serv, chan, word[1].lower()) )
        print("removed")
    else:
        print("didn't existed, so not removed")
    # print("INFO '%s' '%s' '%s'" % (serv, chan, who) )
    return hexchat.EAT_ALL

def gateway_clear(word, word_eol, userdata):
    global chans, chans2
    chans = set()
    chans2 = set()
    return hexchat.EAT_ALL

def file_load(replace = False):
    global dir, chans, chans2
    if replace:
        chans = set()
        chans2 = set()
    with open(dir+"\\gateway1.tsv", "a+") as f:
        f.seek(0)
        f.readline()
        for line in f:
            line = line.strip("\r\n")
            line2=line.split("\t")
            elem = ( line2[0], line2[1], line2[2])
            if elem not in chans:
                chans.add( elem )
    with open(dir+"\\gateway2.tsv", "a+") as f:
        f.seek(0)
        f.readline()
        for line in f:
            line = line.strip("\r\n")
            line2=line.split("\t")
            elem = ( line2[0], line2[1], line2[2])
            if elem not in chans2:
                chans2.add( elem )
    return True

def file_save():
    global dir, chans, chans2
    with open(dir+"\\gateway1.tsv", "w+") as f:
        f.write("server\tchannel\tbridge\n")
        for elem in chans:
            f.write(elem[0]+"\t"+elem[1]+"\t"+elem[2]+"\n")
    with open(dir+"\\gateway2.tsv", "w+") as f:
        f.write("server\tchannel\tbridge\n")
        for elem in chans2:
            f.write(elem[0]+"\t"+elem[1]+"\t"+elem[2]+"\n")
    return True

def gateway_load(word, word_eol, userdata):
    global chans, chans2
    file_load()
    print("loading gateway_support data")
    return hexchat.EAT_ALL

def gateway_reload(word, word_eol, userdata):
    global chans, chans2
    file_load(True)
    print("reloading gateway_support data")
    return hexchat.EAT_ALL

def gateway_save(word, word_eol, userdata):
    global chans, chans2
    file_save()
    print("Saving gateway_support data")
    return hexchat.EAT_ALL

def gateway_list(word, word_eol, userdata):
    global chans
    ms=len("Server")
    mc=len("Channel")
    mn=len("Nick")
    count=0
    for elem in chans:
        if len(elem[0]) > ms:
            ms = len(elem[0])
        if len(elem[1]) > mc:
            mc = len(elem[1])
        if len(elem[2]) > mn:
            mn = len(elem[2])
        count+=1
    for elem in chans2:
        if len(elem[0]) > ms:
            ms = len(elem[0])
        if len(elem[1]) > mc:
            mc = len(elem[1])
        if len(elem[2]) > mn:
            mn = len(elem[2])
        count+=1
    print("{0:<{1}} {2:<{3}} {4:<{5}}\n{6:{7}<{1}} {6:{7}<{3}} {6:{7}<{5}}".format("Server", ms, "Channel", mc, "Nick", mn, "#", "#"))
    for elem in chans:
        print("{0:<{1}} {2:<{3}} {4:<{5}}".format(elem[0], ms, elem[1], mc, elem[2], mn))
    for elem in chans2:
        print("{0:<{1}} {2:<{3}} {4:<{5}}".format(elem[0], ms, elem[1], mc, elem[2], mn))
    if count==0:
        print("List is empty.")
    return hexchat.EAT_ALL

def unload_cb(userdata):
    global chans, chans2
    print("Unloading gateway_support")
    hexchat.command('MENU DEL "$NICK/Use Gateway"')
    #gateway_save([], [], [])
    return hexchat.EAT_ALL

hexchat.hook_unload(unload_cb)

gateway_load([], [], [])

hexchat.hook_command('gateway_on', gateway_on, help="/gateway_on <nick> Add the user as the gateway for the channel")
hexchat.hook_command('gateway_off', gateway_off, help="/gateway_off <nick> Remove the user as the gateway for the channel")
hexchat.hook_command('gateway_on2', gateway_on2, help="/gateway_on2 <nick> Add the user as the gateway [prefixed] for the channel")
hexchat.hook_command('gateway_off2', gateway_off2, help="/gateway_off2 <nick> Remove the user as the gateway [prefixed] for the channel")

hexchat.hook_command('gateway_save', gateway_save, help="/gateway_save Save the gateway list for all servers / channel")
hexchat.hook_command('gateway_load', gateway_load, help="/gateway_load Read the gateway list for all servers / channel (WARNING: overwrite content)")

hexchat.hook_command('gateway_reload', gateway_reload, help="/gateway_reload Read the gateway list for all servers / channel (WARNING: append content)")
hexchat.hook_command('gateway_clear', gateway_clear, help="/gateway_clear Clears the gateway list for all servers / channel (WARNING: delete content)")

hexchat.hook_command('gateway_list', gateway_list, help="/gateway_list List the gateway list for all servers / channel")

#hexchat.command('MENU -t0 ADD "$NICK/Use as gateway" "gateway_on %s" "gateway_off %s"')

hexchat.hook_print(chmsg , un_gateway_ize, {"msgtype": chmsg })
hexchat.hook_print(chmsg2, un_gateway_ize, {"msgtype": chmsg2})
hexchat.hook_print(chnot, un_gateway_ize, {"msgtype": chnot})
hexchat.hook_print(chact , un_gateway_ize, {"msgtype": chact })
hexchat.hook_print(chact2, un_gateway_ize, {"msgtype": chact2})
# hexchat.hook_print(pract, un_gateway_ize, {"msgtype": pract})
# hexchat.hook_print(prmsg, un_gateway_ize, {"msgtype": prmsg})
