import React from 'react';
import { StyleSheet, Text, View, useWindowDimensions } from 'react-native';


export default function interfaz_warehouse() {
  const { width } = useWindowDimensions();

  return (
    <View style={styles.container}>
      <View style={styles.row}>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(42, 126, 209)' }]} onPress={() => alert('Hola 1')}>Almacenes</Text>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(209, 53, 42)' }]} onPress={() => alert('Hola 1')}>Editar Almacen</Text>
      </View>
      <View style={styles.row}>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(42, 126, 209)' }]} onPress={() => alert('Hola 1')}>Hola</Text>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(209, 53, 42)' }]} onPress={() => alert('Hola 1')}>Hola</Text>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
   
    padding: 20,
  },
  labelText: {
    labelText: {
      color: 'white', // Asegúrate de que el texto sea visible
      height: 150,
      width: 150,
      
      borderRadius: 30,
      justifyContent: 'center',
      alignItems: 'center',
      display: 'flex',
      textAlign: 'center',
      lineHeight: 10,
      fontSize: 10,
    },
  },
  row: {
    flexDirection: 'row',  // Cambié a 'row' para que los elementos estén en fila.
    justifyContent: 'center',
    alignItems: 'center',
    gap: 30,  // Opcional: Para agregar espacio entre los elementos en la fila
    marginBottom: 30,
    marginTop: 10
  }
});
