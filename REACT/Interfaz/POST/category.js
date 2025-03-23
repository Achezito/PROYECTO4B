import axios from 'axios';
import { StatusBar } from 'expo-status-bar';
import React, { useState } from 'react';
import { Button, StyleSheet, Text, TextInput, View } from 'react-native';

const url = "http://localhost/PROYECTO4B-1/phpfiles/react/category_api.php";

export default function App() {

  const [name, setName] = useState('');
  const [description, setDescription] = useState('');

  const handleCategory = () => {
    if (!name || !description) {
      alert("Por favor, completa todos los campos.");
      return;
    }

    const newCategory = { name, description };

    axios.post(url, newCategory)
      .then((response) => {
        alert(`Categoría creada: ${name} - ${description}`);
        setName('');
        setDescription('');
      })
      .catch((error) => {
        alert("No se pudo crear la categoría.");
      });
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Crear Nueva Categoría</Text>

      <TextInput
        style={styles.input}
        placeholder='Nombre de la categoría'
        value={name}
        onChangeText={setName}
        placeholderTextColor="#aaa"
      />

      <TextInput
        style={styles.input}
        placeholder='Descripción'
        value={description}
        onChangeText={setDescription}
        placeholderTextColor="#aaa"
      />

      <View style={styles.buttonContainer}>
        <Button title='Crear Categoría' onPress={handleCategory} color="#007bff" />
      </View>

      <StatusBar style="auto" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f0f4f8',
    padding: 20,
    justifyContent: 'center',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
    textAlign: 'center',
  },
  input: {
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 12,
    marginBottom: 15,
    fontSize: 16,
    borderColor: '#ccc',
    borderWidth: 1,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4,
  },
  buttonContainer: {
    borderRadius: 12,
    overflow: 'hidden',
    marginTop: 10,
  }
});
