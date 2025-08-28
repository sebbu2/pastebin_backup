package jd.plugins.decrypter;

import java.util.ArrayList;
import java.util.Base64;

import jd.PluginWrapper;
import jd.controlling.ProgressController;
import jd.controlling.linkcrawler.CrawledLink;
import jd.parser.Regex;
import jd.plugins.CryptedLink;
import jd.plugins.DecrypterPlugin;
import jd.plugins.DownloadLink;
import jd.plugins.PluginForDecrypt;

@DecrypterPlugin(revision = "$Revision: 36558 $", interfaceVersion = 2, names = { "javcl.com" }, urls = { "https?://(?:www\\.)?javcl\\.com/([^/]+)/" })
public class JavCL extends PluginForDecrypt {
    public JavCL(PluginWrapper wrapper) {
        super(wrapper);
    }

    @Override
    public ArrayList<DownloadLink> decryptIt(CryptedLink parameter, ProgressController progress) throws Exception {
        final ArrayList<DownloadLink> ret = new ArrayList<DownloadLink>();
        br.getPage(parameter.getCryptedUrl());
        String filename = br.getRegex("<span class=\"title2\">([a-zA-Z0-9-]+)</span>").getMatch(0);
        String data_id = br.getRegex("<div id='videoPlayer' data-id=\"([0-9]+)\" data-ep=\"([0-9]+)\">").getMatch(0);
        Regex data_links = br.getRegex("<li data-sv=\"([0-9]+)\" data-link=\"([a-zA-Z0-9/+=-]+)\"(?: class=\"active\")?>\\s*([^</]+)\\s*</li>");
        int index = 0;
        for (String data_link : data_links.getColumn(1)) {
            String _link = decodejav(data_link, data_id);
            CryptedLink _link2 = new CryptedLink(_link);
            jd.plugins.decrypter.FEmbedDecrypter plugin = new jd.plugins.decrypter.FEmbedDecrypter(getLazyC().getPluginWrapper());
            plugin.setBrowser(br);
            if (data_links.count() > 1) {
                plugin.setFilename(filename + "_" + (char) ('a' + index));
            } else {
                plugin.setFilename(filename);
            }
            ArrayList<DownloadLink> ret2 = plugin.decryptIt(new CrawledLink(_link2));
            ret.addAll(ret2);
            index++;
        }
        return ret;
    }

    public String decodejav(String data_link, String data_id) {
        String key = Base64.getEncoder().encodeToString((data_id + "decodejav").getBytes());
        key = new StringBuilder(key).reverse().toString();
        byte[] link = Base64.getDecoder().decode(data_link);
        StringBuilder sb = new StringBuilder();
        int k = 0;
        for (int i = 0; i < link.length; i++) {
            k = i % key.length();
            sb.append((char) (link[i] ^ key.codePointAt(k)));
        }
        return new String(Base64.getDecoder().decode(sb.toString()));
    }
}
