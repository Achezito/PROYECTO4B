import axios from 'axios';
import { StatusBar } from 'expo-status-bar';
import React, { useState } from 'react';
import { Button, StyleSheet, Text, TextInput, View } from 'react-native';

const url = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php"; // Asegúrate de que la URL sea correcta

export default function App() {

  const [orderDate, setOrderDate] = useState('');
  const [idStatus, setIdStatus] = useState('');
  const [idSupply, setIdSupply] = useState('');
  const [quantity, setQuantity] = useState('');

  const handleOrder = () => {
    if (!orderDate || !idStatus || !idSupply || !quantity) {
      alert("Por favor, completa todos los campos.");
      return;
    }

    const newOrder = {
      order_date: orderDate,
      id_status: parseInt(idStatus),
      id_supply: parseInt(idSupply),
      quantity: parseInt(quantity)
    };

    axios.post(url, newOrder)
      .then((response) => {
        alert(response.data.message);
        setOrderDate('');
        setIdStatus('');
        setIdSupply('');
        setQuantity('');
      })
      .catch((error) => {
        alert(error.response?.data?.error || "No se pudo crear la orden.");
      });
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Crear Nueva Orden</Text>

      <TextInput
        style={styles.input}
        placeholder='Fecha de Orden (YYYY-MM-DD)'
        value={orderDate}
        onChangeText={setOrderDate}
        placeholderTextColor="#aaa"
      />

      <TextInput
        style={styles.input}
        placeholder='ID Estado'
        value={idStatus}
        onChangeText={setIdStatus}
        placeholderTextColor="#aaa"
        keyboardType='numeric'
      />

      <TextInput
        style={styles.input}
        placeholder='ID Suministro'
        value={idSupply}
        onChangeText={setIdSupply}
        placeholderTextColor="#aaa"
        keyboardType='numeric'
      />

      <TextInput
        style={styles.input}
        placeholder='Cantidad'
        value={quantity}
        onChangeText={setQuantity}
        placeholderTextColor="#aaa"
        keyboardType='numeric'
      />

      <View style={styles.buttonContainer}>
        <Button title='Crear Orden' onPress={handleOrder} color="#007bff" />
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
