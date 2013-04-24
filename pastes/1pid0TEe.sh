#!/bin/bash

sed -sri 's/sf::WindowSettings/sf::ContextSettings/g' *.{hpp,cpp}
sed -sri 's/->LoadFromFile/->loadFromFile/g' *.{hpp,cpp}
sed -sri 's/\.LoadFromFile/.loadFromFile/g' *.{hpp,cpp}
sed -sri 's/->GetPixel/->getPixel/g' *.{hpp,cpp}
sed -sri 's/->SetPixel/->setPixel/g' *.{hpp,cpp}
sed -sri 's/->CreateMaskFromColor/->createMaskFromColor/g' *.{hpp,cpp}
sed -sri 's/\.Left/.left/g' *.{hpp,cpp}
sed -sri 's/\.Top/.top/g' *.{hpp,cpp}
sed -sri 's/sf::Image/sf::Texture/g' *.{hpp,cpp}
sed -sri 's/->SetSmooth/->setSmooth/g' *.{hpp,cpp}
sed -sri 's/\.IsSmooth/.isSmooth/g' *.{hpp,cpp}
sed -sri 's/\.SetImage/.setTexture/g' *.{hpp,cpp}
sed -sri 's/\.SetSubRect/.setTextureRect/g' *.{hpp,cpp}
sed -sri 's/\.SetPosition/.setPosition/g' *.{hpp,cpp}
sed -sri 's/->SetFramerateLimit/->setFramerateLimit/g' *.{hpp,cpp}
sed -sri 's/->GetView/->getView/g' *.{hpp,cpp}
sed -sri 's/Event\.Type/Event.type/g' *.{hpp,cpp}
sed -sri 's/Event\.Key/Event.key/g' *.{hpp,cpp}
sed -sri 's/Event\.key\.Code/Event.key.code/g' *.{hpp,cpp}
sed -sri 's/sf::Key/sf::Keyboard/g' *.{hpp,cpp}
sed -sri 's/->Close/->close/g' *.{hpp,cpp}
sed -sri 's/->Clear/->clear/g' *.{hpp,cpp}
sed -sri 's/->Draw/->draw/g' *.{hpp,cpp}
sed -sri 's/->Display/->display/g' *.{hpp,cpp}
sed -sri 's/->IsOpened/->isOpen/g' *.{hpp,cpp}
sed -sri 's/->GetEvent/->pollEvent/g' *.{hpp,cpp}
sed -sri 's/\.GetRect/.getViewport /g' *.{hpp,cpp}
sed -sri 's/\.SetRect/.setViewport /g' *.{hpp,cpp}
sed -sri 's/\.Move/.move/g' *.{hpp,cpp}
sed -sri 's/\.SetView/.setView/g' *.{hpp,cpp}
sed -sri 's/->SetView/->setView/g' *.{hpp,cpp}
sed -sri 's/\.SetScale/.setScale/g' *.{hpp,cpp}
